<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe Webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Log the event for debugging
        Log::info('Stripe Webhook Received', [
            'type' => $event->type,
            'id' => $event->id
        ]);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Stripe Webhook: Unhandled event type', ['type' => $event->type]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful checkout session
     */
    protected function handleCheckoutCompleted($session)
    {
        Log::info('Processing checkout.session.completed', [
            'session_id' => $session->id,
            'customer_email' => $session->customer_email ?? 'N/A',
            'client_reference_id' => $session->client_reference_id ?? 'N/A',
        ]);

        $user = null;

        // 1. Try to find by client_reference_id (most reliable)
        if (isset($session->client_reference_id)) {
            $user = User::find($session->client_reference_id);
        }

        // 2. Fallback to email
        if (!$user && isset($session->customer_email)) {
            $user = User::where('email', $session->customer_email)->first();
        }

        if (!$user) {
            Log::warning('User not found for checkout session', [
                'email' => $session->customer_email ?? 'N/A',
                'client_reference_id' => $session->client_reference_id ?? 'N/A'
            ]);
            return;
        }

        // Update user to premium
        $user->update([
            'is_premium' => true,
            'premium_expires_at' => now()->addMonth(), // 1 month subscription
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $session->subscription ?? null,
        ]);

        Log::info('User upgraded to premium', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
    }

    /**
     * Handle subscription updates
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        $user = User::where('stripe_subscription_id', $subscription->id)->first();

        if (!$user) {
            // Try to find by customer ID
            $user = User::where('stripe_customer_id', $subscription->customer)
                ->orWhere('stripe_id', $subscription->customer)
                ->first();
        }

        if (!$user) {
            Log::warning('User not found for subscription update', [
                'subscription_id' => $subscription->id
            ]);
            return;
        }

        // Update premium status based on subscription status
        $isActive = in_array($subscription->status, ['active', 'trialing']);

        $user->update([
            'is_premium' => $isActive,
            'premium_expires_at' => ($isActive && isset($subscription->current_period_end))
                ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)
                : null,
            'stripe_subscription_id' => $subscription->id,
        ]);

        Log::info('Subscription updated', [
            'user_id' => $user->id,
            'status' => $subscription->status,
            'is_premium' => $isActive
        ]);
    }

    /**
     * Handle subscription cancellation
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        Log::info('Webhook: Handling customer.subscription.deleted', [
            'subscription_id' => $subscription->id,
            'customer' => $subscription->customer ?? null,
        ]);

        $user = User::where('stripe_subscription_id', $subscription->id)
            ->orWhere('stripe_customer_id', $subscription->customer)
            ->orWhere('stripe_id', $subscription->customer)
            ->first();

        if (!$user) {
            Log::warning('Webhook: User not found for subscription cancellation', ['customer' => $subscription->customer]);
            return;
        }

        $user->update([
            'is_premium' => false,
            'premium_expires_at' => null,
            'stripe_subscription_id' => null, // Clear subscription ID to reflect state
        ]);

        Log::info('Subscription cancelled', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id
        ]);
    }

    /**
     * Handle successful invoice payment
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Webhook: Handling invoice.payment_succeeded', [
            'invoice_id' => $invoice->id,
            'customer' => $invoice->customer,
            'subscription' => $invoice->subscription
        ]);

        $user = User::where('stripe_customer_id', $invoice->customer)
            ->orWhere('stripe_id', $invoice->customer)
            ->first();

        if (!$user) {
            Log::warning('Webhook: User not found for invoice', ['customer' => $invoice->customer]);
            return;
        }

        Log::info('Webhook: User found', ['user_id' => $user->id]);

        // Check for subscription OR if it's a one-time payment for a plan
        $plan = null;
        if (isset($invoice->lines) && isset($invoice->lines->data)) {
            foreach ($invoice->lines->data as $line) {
                if (isset($line->price->id)) {
                    $plan = \App\Models\Plan::where('stripe_id', $line->price->id)->first();
                    if ($plan)
                        break;
                }
            }
        }

        // Extend premium if it's a recurring payment (subscription) OR we found a valid plan in lines OR it's a paid invoice
        if ($invoice->subscription || $plan || $invoice->amount_paid > 0) {
            $expiration = now()->addMonth(); // Default fall back

            if ($plan) {
                if ($plan->interval === 'year' || $plan->interval === 'yearly') {
                    $expiration = now()->addYear();
                } else {
                    // Default to month if not explicitly yearly
                    $expiration = now()->addMonth();
                }
            }

            $user->update([
                'is_premium' => true,
                'premium_expires_at' => $expiration,
            ]);

            Log::info('Premium renewed via invoice payment', [
                'user_id' => $user->id,
                'plan' => $plan ? $plan->name : 'Unknown/Legacy',
                'new_expires_at' => $user->premium_expires_at,
                'subscription_id' => $invoice->subscription // null if not present
            ]);
        } else {
            Log::info('Webhook: Invoice handled but no action taken (amount 0 or no plan)', ['invoice_id' => $invoice->id]);
        }
    }

    /**
     * Handle failed invoice payment
     */
    protected function handleInvoicePaymentFailed($invoice)
    {
        $user = User::where('stripe_customer_id', $invoice->customer)
            ->orWhere('stripe_id', $invoice->customer)
            ->first();

        if (!$user) {
            return;
        }

        Log::warning('Invoice payment failed', [
            'user_id' => $user->id,
            'invoice_id' => $invoice->id
        ]);

        // Optionally send notification to user about failed payment
    }
}
