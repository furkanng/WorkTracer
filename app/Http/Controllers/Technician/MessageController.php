<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Mail\NewMessageReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index()
    {
        $users = User::query()->whereNot('id', auth()->id())->get();
        return view('secretary.messages.index', compact('users'));
    }

    public function show(User $user)
    {
        // Mesajları getir ve okunmamışları okundu olarak işaretle
        $messages = Message::where(function($query) use ($user) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $user->id)
              ->where('receiver_id', auth()->id())
              ->where('is_read', false)
              ->update(['is_read' => true]);

        return view('technician.messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $validated['content']
        ]);

        try {
            Mail::to($user->email)->send(new NewMessageReceived($message));
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Mesaj gönderildi.');
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        $message->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
} 