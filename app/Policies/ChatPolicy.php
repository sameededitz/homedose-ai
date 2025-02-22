<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view any chats.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view their own chats
    }

    /**
     * Determine whether the user can view a specific chat.
     */
    public function view(User $user, Chat $chat): Response
    {
        return $user->id === $chat->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to view this chat.');
    }

    /**
     * Determine whether the user can create a chat.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create chats
    }

    /**
     * Determine whether the user can update a chat.
     */
    public function update(User $user, Chat $chat): Response
    {
        return $user->id === $chat->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this chat.');
    }

    /**
     * Determine whether the user can delete a chat.
     */
    public function delete(User $user, Chat $chat): Response
    {
        return $user->id === $chat->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this chat.');
    }
}
