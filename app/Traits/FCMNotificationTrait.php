<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait FCMNotificationTrait
{

    public function sendFCMNotification(array $fcmTokens, $title, $body, $data = [])
    {
        // Set the FCM endpoint URL
        $fcmEndpoint = 'https://fcm.googleapis.com/fcm/send';

        // Create the notification payload
        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        // Create the complete payload
        $payload = [
            'registration_ids' => $fcmTokens,
            'notification' => $notification,
            'data' => $data,
        ];

        // Send the HTTP request to FCM
        $response = Http::withHeaders([
            'Authorization' => 'key=YOUR_SERVER_KEY',
            'Content-Type' => 'application/json',
        ])->post($fcmEndpoint, $payload);

        // Check the response status and handle any errors if needed
        if ($response->successful()) {
            // Notification sent successfully
            return true;
        } else {
            // Handle the error
            return false;
        }
    }

}
