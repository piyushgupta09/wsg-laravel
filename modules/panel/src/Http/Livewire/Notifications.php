<?php

namespace Fpaipl\Panel\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Notifications extends Component
{
    public $type;
    public $notifications;
    public $hasNotification;
    public $unreadCount;

    protected $listeners = ['updateNotificationCount' => 'handleNotificationUpdate'];

    public function handleNotificationUpdate($data=null)
    {
        $this->loadNotifications($data);
    }   

    public function mount()
    {
        $this->type = 'all'; // 'read', 'unread
        $this->hasNotification = false;
        $this->loadNotifications();
    }

    private function loadNotifications($data=null)
    {
        if ($data) {
            Log::info("loadNotifications" . $data['message']);
        } else {
            Log::info("loadNotifications");
        }
        
        /** @var User $currentUser */
        $currentUser = auth()->user();
        
        // Eager load notifications from the last 7 days
        $currentUser->load(['notifications' => function ($query) {
            $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                ->orderBy('created_at', 'desc');
        }]);

        $this->unreadCount = $currentUser->unreadNotifications->count();
        $this->hasNotification = $this->unreadCount > 0;
        Log::info("unread count: " . $this->unreadCount);
        $this->notifications = $currentUser->notifications
            ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy(fn($item) => $item->read_at ? 'read' : 'unread')
            ->map(fn($group) => $group->map(fn($notification) => 
                array_merge($notification->data, ['id' => $notification->id])
            ))
            ->toArray();
        
        // loop thru all notifications and if its not todays then mark as read
        foreach ($currentUser->notifications as $notification) {
            if ($notification->created_at->format('Y-m-d') != \Carbon\Carbon::now()->format('Y-m-d')) {
                $notification->markAsRead();
            }
        }
    }

    public function markAsRead($notificationId)
    {
        // dd($notificationId);
        /** @var User $currentUser */
        $currentUser = auth()->user();
        $notification = $currentUser->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            return redirect()->to($notification->data['action']);
        }
    }   

    public function getTimeAgo($timestamp)
    {
        return \Carbon\Carbon::parse($timestamp)->diffForHumans();
    }
    
    public function render()
    {
        return view('panel::livewire.notifications', [
            'notifications' => $this->notifications,
            'hasNotification' => $this->hasNotification,
        ]);
    }
}
