<?php

namespace Fpaipl\Authy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Resources\NotificationResource;

class NotificationCoordinator extends Coordinator
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
    
        $perPage = $request->get('perpage', 10);
        $search = $request->get('search', null);
        $status = $request->get('status', 'unread');
        $type = $request->get('filter', 'all');
    
        $typeClassMap = [
            'order' => 'Fpaipl\Shopy\Notifications\SendOrderNotification',
            'payment' => 'Fpaipl\Shopy\Notifications\SendPaymentNotification',
            'delivery' => 'Fpaipl\Shopy\Notifications\SendDeliveryNotification',
        ];
    
        // Start with all notifications
        $query = $user->notifications();
    
        // If a specific filter is requested and exists in the type map, apply it
        if ($type !== 'all' && array_key_exists($type, $typeClassMap)) {
            $query->where('type', $typeClassMap[$type]);
        }

        // If a search term is provided, apply it
        if ($search) {
            $query->where('data', 'like', "%$search%");
        }

        // If a status is provided, apply it
        if ($status === 'unread') {
            $query->whereNull('read_at');
        } elseif ($status === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate($perPage);
    
        return ApiResponse::success([
            'data' => NotificationResource::collection($notifications),
            'pagination' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
            ]
        ]);
    }    
    
    public function unread()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->unreadNotifications()->get();
        return ApiResponse::success(
            NotificationResource::collection($notifications)
        );
    }

    public function markRead(Request $request, $notification)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->notifications()->where('id', $notification)->update(['read_at' => now()]);
        return ApiResponse::success('Notification marked as read');
    }

    public function markAllRead(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        return ApiResponse::success('All notifications marked as read');
    }

    public function pusherAuth(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        // $channel = $user->isBrand() ? $user->uuid : $user->party->uuid;

        if ($user->isBrand()) {
            $event ='brand-event';
        }

        if ($user->isParty()) {
            $event = 'party-event';
        }
    
        return ApiResponse::success([
            'event' => $event,
            // 'channel' => $channel,
            'channel' => $user->uuid,
            'key' => config('pusher.app_key'),
        ]);
    }

}