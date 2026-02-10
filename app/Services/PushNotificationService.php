<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Sends a push notification to a specific user.
     *
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param string $url
     * @return bool
     */
    public function sendPush(int $userId, string $title, string $body, string $url = '/')
    {
        $user = User::find($userId);

        if (!$user) {
            Log::warning("Trying to send push to non-existent user ID: {$userId}");
            return false;
        }

        if ($user->pushSubscriptions()->count() === 0) {
            Log::info("User ID: {$userId} has no active push subscriptions.");
            return false;
        }

        try {
            $user->notify(new PushNotification($title, $body, $url));
            return true;
        } catch (\Exception $e) {
            Log::error("Error sending push to user {$userId}: " . $e->getMessage());
            return false;
        }
    }
}
