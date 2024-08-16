<?php

namespace App\Services;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDevices;

class FcmNotificationService
{
    public function __construct()
    {
       
    }

    public function sendFcmNotification($body)
    {
        $user = UserDevices::where('user_id',$body['receiver_id'])->where('status',1)->get();

        // $fcm = '';
        foreach ($user as $tokens) {
            $fcm[] = $tokens['device_token'];
        }
        $fcm = isset($fcm) ? implode(', ',$fcm) : '';
        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }
        $title = $body['title'];
        $description = $body['message'];
        $newArrData = isset($body['data']) ? (object)$body['data'] : '';
        // dd($newArrData);
        $projectId = config('services.fcm.project_id'); # INSERT COPIED PROJECT ID

        $credentialsFilePath = Storage::path('json/google-services.json');
       
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
                "data" => $newArrData,
            ]
        ];
        $payload = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}
