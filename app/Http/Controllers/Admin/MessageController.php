<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages       = Message::latest()->paginate(20);
        $unreadMessages = Message::where('is_read', false)->count();
        $pageTitle      = 'Messages';
        return view('admin.messages.index', compact('messages', 'unreadMessages', 'pageTitle'));
    }

    public function show(Message $message)
    {
        $message->update(['is_read' => true]);
        $unreadMessages = Message::where('is_read', false)->count();
        $pageTitle      = 'Message from ' . $message->name;
        return view('admin.messages.show', compact('message', 'unreadMessages', 'pageTitle'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
