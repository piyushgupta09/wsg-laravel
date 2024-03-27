<?php

namespace Fpaipl\Panel\Http\Controllers;

use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Panel\Datatables\NotificationDatatable as Datatable;

class NotificationsController extends PanelController
{
    public function __construct()
    {
        // $this->middleware('role:admin');

        parent::__construct(
            new Datatable(), 
            'Fpaipl\Panel\Models\Notification', 
            'notification', 'notifications.index'
        );
    }

    public function show($type)
    {
        $currentUser = auth()->user();

        switch ($type) {
            case 'all':
                $notifications = $currentUser->notifications;
                break;
            case 'unread':
                $notifications = $currentUser->unreadNotifications;
                break;
            case 'read':
                $notifications = $currentUser->readNotifications;
                break;
            default:
                $notifications = $currentUser->notifications;
                break;
        }

        return view('panel::pages.notifications', compact('notifications'));
    }
}
