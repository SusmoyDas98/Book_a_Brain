<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function getRoleRecord()
    {
        $role = Auth::user()->role;
        return match($role) {
            'guardian' => Auth::user()->guardian,
            'tutor'    => Auth::user()->tutor,
            'admin'    => Auth::user()->admin,
            default    => null,
        };
    }

    public function index()
    {
        $role       = Auth::user()->role;
        $roleRecord = $this->getRoleRecord();

        if (! $roleRecord) {
            abort(403);
        }

        $notifications = AppNotification::forRecipient($role, $roleRecord->getKey())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications', 'role'));
    }

    public function markRead($id)
    {
        $role       = Auth::user()->role;
        $roleRecord = $this->getRoleRecord();

        if (! $roleRecord) {
            abort(403);
        }

        $notification = AppNotification::findOrFail($id);

        if ($notification->recipient_type !== $role || (int) $notification->recipient_id !== (int) $roleRecord->getKey()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->back();
    }

    public function markAllRead()
    {
        $role       = Auth::user()->role;
        $roleRecord = $this->getRoleRecord();

        if (! $roleRecord) {
            abort(403);
        }

        AppNotification::forRecipient($role, $roleRecord->getKey())
            ->unread()
            ->each(fn($n) => $n->markAsRead());

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function unreadCount()
    {
        $role       = Auth::user()->role;
        $roleRecord = $this->getRoleRecord();

        if (! $roleRecord) {
            return response()->json(['count' => 0]);
        }

        $count = AppNotification::forRecipient($role, $roleRecord->getKey())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}
