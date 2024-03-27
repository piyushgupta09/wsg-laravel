<?php

namespace Fpaipl\Panel\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fpaipl\Panel\Jobs\AdminNotificationJob;

class PusherController extends Controller
{
    public function push(Request $request)
    {
        AdminNotificationJob::dispatch(
            'Pusher Notification', 
            'Job is triggered, which is queued and it lauches an event,
             which is listened by the Pusher client and it shows the notification.'
        );
        return redirect()->route('panel.dashboard')->with('success', true);
    }
}
