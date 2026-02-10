<?php

namespace App\Jobs;

use App\Models\PushCampaign;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBroadcastPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $service;

    /**
     * Create a new job instance.
     */
    public function __construct(PushCampaign $campaign)
    {
        $this->campaign = $campaign;
        $this->service = new PushNotificationService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = User::query();

        // Target Segments Logic
        switch ($this->campaign->target_segment) {
            case 'free':
                $query->where('is_premium', false);
                break;
            case 'premium':
                $query->where('is_premium', true);
                break;
            case 'test_user':
                $query->where('id', $this->campaign->created_by);
                break;
            case 'all':
            default:
                // No extra filter
                break;
        }

        // Only users with subscriptions
        $query->whereHas('pushSubscriptions');

        $totalSent = 0;

        $query->chunk(100, function ($users) use (&$totalSent) {
            foreach ($users as $user) {
                $success = $this->service->sendPush(
                    $user->id,
                    $this->campaign->title,
                    $this->campaign->body
                );

                if ($success) {
                    $totalSent++;
                }
            }
        });

        $this->campaign->update([
            'sent_count' => $totalSent
        ]);

        Log::info("Push Broadcast Campaign #{$this->campaign->id} finished. Sent: {$totalSent}");
    }
}
