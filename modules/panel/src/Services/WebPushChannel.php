<?php

namespace Fpaipl\Panel\Services;


use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Fpaipl\Panel\Notifications\WebPushNotification;

class WebPushChannel
{

    protected $webPush;

    public function __construct(WebPush $webPush)
    {
        $this->webPush = $webPush;
    }


    public function send($notifiable, WebPushNotification $notification)
    {
        // Logic to send web push notification
        // $notifiable is the user or model being notified
        // $notification contains the notification content

        // Example: Send a web push notification using your logic
        // $notification->toWebPush($notifiable);

        /** @var \Illuminate\Database\Eloquent\Collection $subscriptions */
        $subscriptions = $notifiable->routeNotificationFor('WebPush', $notification);


        /** @var \NotificationChannels\WebPush\WebPushMessage $message */
        $message = $notification->toWebPush($notifiable, $notification);
        $payload = json_encode($message->toArray());
        $options = $message->getOptions();


        /** @var \NotificationChannels\WebPush\PushSubscription $subscription */
        foreach ($subscriptions as $subscription) {
            $this->webPush->queueNotification(new Subscription(
                $subscription->endpoint,
                $subscription->public_key,
                $subscription->auth_token,
                $subscription->content_encoding
            ), $payload, $options);
        }

        $reports = $this->webPush->flush();

        // Process the reports to handle success or failure of notifications
        foreach ($reports as $report) {
            if ($report->isSuccess()) {
                // echo 'success';
                // Notification was successfully sent
                //  echo $report->getRequest()->getUri(); 
                //  exit;
                 //provides the endpoint
            } else {
                // Notification failed, handle errors
                // echo 'fail';
                //  echo $report->getReason(); 
                //  exit;
                 //provides the reason for failure
            }
        }
    }
}
