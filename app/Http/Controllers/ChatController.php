<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function chats()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $chats = $user->chats()->with('messages')->get();

        return response()->json([
            'status' => true,
            'chats' => $chats
        ]);
    }

    public function show(Chat $chat)
    {
        Gate::authorize('view', $chat);

        $chat->load('messages');

        return response()->json([
            'status' => true,
            'chat' => $chat
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:999'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $chat = $user->chats()->create([
            'title' => $request->title
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Chat created successfully',
            'chat' => $chat
        ]);
    }

    public function message(Request $request, Chat $chat)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:9999',
            'sender' => 'required|in:ai,user',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:30720'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        Gate::authorize('view', $chat);

        /** @var \App\Models\Message $message **/
        $message = $chat->messages()->create([
            'message' => $request->message,
            'sender' => $request->sender,
        ]);

        if ($request->hasFile('image')) {
            $message->clearMediaCollection('image');
            $message->addMedia($request->file('image'))
                ->usingFileName(time() . '_chat_' . $chat->id . '_message_' . $message->id . '.' . $request->file('image')->getClientOriginalExtension())
                ->toMediaCollection('image');
        }

        return response()->json([
            'status' => true,
            'message' => 'Message saved successfully',
            'chat' => $chat->load('messages'),
            'messages' => $message
        ], 201);
    }

    public function destroy(Chat $chat)
    {
        if (!$chat) {
            return response()->json([
                'status' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        Gate::authorize('delete', $chat);

        // Delete all associated media
        foreach ($chat->messages as $message) {
            $message->clearMediaCollection('image');
        }

        $chat->delete();
        return response()->json([
            'status' => true,
            'message' => 'Chat deleted successfully'
        ]);
    }
}
