<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            'unread_count' => Auth::user()->unreadNotifications->count(),
            'notifications' => Auth::user()->unreadNotifications->take(10)->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->data['title'],
                    'message' => $n->data['message'],
                    'url' => $n->data['url'] ?? '#',
                    'icon' => $n->data['icon'] ?? 'bell',
                    'color' => $n->data['color'] ?? 'gray',
                    'created_at' => $n->created_at->diffForHumans(),
                ];
            })
        ]);
        ]);
    }

    public function check()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['count' => 0]);

        $latest = $user->unreadNotifications()->latest()->first();
        
        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'latest' => $latest ? [
                'title' => $latest->data['title'] ?? 'Notification',
                'message' => $latest->data['message'] ?? '',
                'url' => route('notifications.read', $latest->id), // Click marks as read then redirects
            ] : null
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect to the target URL if available
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }
        return back();
    }
    
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}
