<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\TuitionContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * List all conversations for the current user.
     */
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::where('guardian_id', $userId)
            ->orWhere('tutor_id', $userId)
            ->with(['guardian', 'tutor', 'contract', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        // Calculate unread counts
        $conversations->each(function ($conversation) use ($userId) {
            $conversation->unread_count = $conversation->unreadCountFor($userId);
        });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Start or resume a conversation from an ACTIVE contract.
     */
    public function startConversation(TuitionContract $contract)
    {
        $userId = Auth::id();

        // Ensure the user is a participant on this contract
        if ($contract->guardian_id !== $userId && $contract->tutor_id !== $userId) {
            abort(403, 'You are not a participant of this contract.');
        }

        // Only ACTIVE contracts can have chat
        if ($contract->status !== 'ACTIVE') {
            return back()->with('error', 'Chat is only available for active contracts.');
        }

        // Find existing or create new conversation
        $conversation = Conversation::firstOrCreate(
            [
                'guardian_id' => $contract->guardian_id,
                'tutor_id' => $contract->tutor_id,
                'contract_id' => $contract->id,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Show a conversation thread.
     */
    public function show(Conversation $conversation)
    {
        $userId = Auth::id();

        // Ensure the user is a participant
        if ($conversation->guardian_id !== $userId && $conversation->tutor_id !== $userId) {
            abort(403);
        }

        // Mark all messages from the other user as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->get();
        $otherUser = $conversation->otherUser($userId);
        $contract = $conversation->contract;

        return view('messages.show', compact('conversation', 'messages', 'otherUser', 'contract'));
    }

    /**
     * Send a message in a conversation.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();

        if ($conversation->guardian_id !== $userId && $conversation->tutor_id !== $userId) {
            abort(403);
        }

        $request->validate([
            'body' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // At least body or attachment is required
        if (empty($request->body) && ! $request->hasFile('attachment')) {
            return back()->with('error', 'Please enter a message or attach a file.');
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat_attachments', 'public');
            $extension = strtolower($file->getClientOriginalExtension());
            $attachmentType = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf';
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Poll for new messages (AJAX endpoint).
     */
    public function fetchNew(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();

        if ($conversation->guardian_id !== $userId && $conversation->tutor_id !== $userId) {
            return response()->json([], 403);
        }

        $afterId = $request->query('after_id', 0);

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $afterId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark received messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->where('id', '>', $afterId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages->map(function ($msg) use ($userId) {
            return [
                'id' => $msg->id,
                'body' => $msg->body,
                'sender_name' => $msg->sender->name,
                'sender_avatar' => $msg->sender->avatar,
                'is_mine' => $msg->sender_id === $userId,
                'attachment_path' => $msg->attachment_path ? asset('storage/'.$msg->attachment_path) : null,
                'attachment_type' => $msg->attachment_type,
                'time' => $msg->created_at->format('g:i A'),
                'read' => ! is_null($msg->read_at),
            ];
        }));
    }
}
