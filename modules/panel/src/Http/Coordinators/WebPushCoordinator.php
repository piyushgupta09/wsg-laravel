<?php

namespace Fpaipl\Panel\Http\Coordinators;

use App\Models\User;
use Illuminate\Http\Request;
use Fpaipl\Panel\Models\Webpush;
use Fpaipl\Panel\Notifications\AppNotification;
use Fpaipl\Panel\Notifications\WebPushNotification;

class WebPushCoordinator extends Coordinator
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $contentEncoding = null;
        $endpoint = $request->endpoint;

        $WebPush = WebPush::findByEndpoint($endpoint);

        if ($WebPush && !$user->ownsPushNotification($WebPush)) {
            $WebPush->delete();
        }

        if ($WebPush && $user->ownsPushNotification($WebPush)) {
            $WebPush->public_key = $request->keys['p256dh'];
            $WebPush->auth_token = $request->keys['auth'];
            $WebPush->content_encoding = $contentEncoding;
            $WebPush->save();
        } else {
            WebPush::create([
                'subscribable_type' => 'App\Models\User',
                'subscribable_id' => $user->id,
                'endpoint' => $endpoint,
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
                'content_encoding' => $contentEncoding,
            ]);
        }
        return response()->json(['success' => true], 200);
    }


    public function push(Request $request)
    {
        if ($request->has('users')) {
            foreach ($request->users as $userId) {
                $user = User::findOrFail($userId);
                $user->notify(new WebPushNotification('Bis Sale', 'Offer last till stock last'));
            }
        }
        return redirect()->back();
    }
}
