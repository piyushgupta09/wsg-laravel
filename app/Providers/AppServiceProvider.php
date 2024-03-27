<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Minishlink\WebPush\WebPush;
use Illuminate\Support\ServiceProvider;
use Fpaipl\Panel\Services\WebPushChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->when(WebPushChannel::class)
            ->needs(WebPush::class)
            ->give(function () {
                return (new WebPush(
                    $this->webPushAuth(), [], 30, config('webpush.client_options', [])
                ))->setReuseVAPIDHeaders(true);
            });
    }

    /**
     * Get the authentication details.
     *
     * @return array
     */
    protected function webPushAuth()
    {
        $config = [];
        $webpush = config('webpush');
        $publicKey = $webpush['vapid']['public_key'];
        $privateKey = $webpush['vapid']['private_key'];

        if (!empty($webpush['gcm']['key'])) {
            $config['GCM'] = $webpush['gcm']['key'];
        }

        if (empty($publicKey) || empty($privateKey)) {
            return $config;
        }

        $config['VAPID'] = compact('publicKey', 'privateKey');
        $config['VAPID']['subject'] = $webpush['vapid']['subject'];

        if (empty($config['VAPID']['subject'])) {
            $config['VAPID']['subject'] = url('/');
        }

        if (!empty($webpush['vapid']['pem_file'])) {
            $config['VAPID']['pemFile'] = $webpush['vapid']['pem_file'];

            if (Str::startsWith($config['VAPID']['pemFile'], 'storage')) {
                $config['VAPID']['pemFile'] = base_path($config['VAPID']['pemFile']);
            }
        }

        return $config;
    }

}
