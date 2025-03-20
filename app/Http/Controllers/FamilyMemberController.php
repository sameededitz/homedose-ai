<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FamilyMemberResource;

class FamilyMemberController extends Controller
{
    public function members()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $familyMembers = $user->familyMembers()->get();

        return response()->json([
            'status' => true,
            'family_members' => FamilyMemberResource::collection($familyMembers),
        ], 200);
    }

    public function show(FamilyMember $familyMember)
    {
        Gate::authorize('view', $familyMember);

        // Find the chat for this family member
        $chat = $familyMember->chat;

        if (!$chat) {
            return response()->json([
                'status' => false,
                'message' => 'No chat found for this family member'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'family_member' => new FamilyMemberResource($familyMember),
            'chat' => new ChatResource($chat->load('messages')),
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'relationship' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:30720'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $familyMember = $user->familyMembers()->create([
            'name' => $request->name,
            'gender' => $request->gender,
            'relationship' => $request->relationship,
            'message' => $request->message,
        ]);

        if ($request->hasFile('image')) {
            $familyMember->clearMediaCollection('image');
            $familyMember->addMedia($request->file('image'))
                ->usingFileName(time() . '_family_member_' . $familyMember->id . '.' . $request->file('image')->getClientOriginalExtension())
                ->toMediaCollection('image');
        }

        return response()->json([
            'status' => true,
            'message' => 'Family member added successfully',
            'family_member' => new FamilyMemberResource($familyMember)
        ], 201);
    }

    public function update(Request $request, FamilyMember $familyMember)
    {
        Gate::authorize('update', $familyMember);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'relationship' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:30720'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $familyMember->update($request->only(['name', 'gender', 'relationship', 'message']));

        if ($request->hasFile('image')) {
            $familyMember->clearMediaCollection('image');
            $familyMember->addMedia($request->file('image'))
                ->usingFileName(time() . '_family_member_' . $familyMember->id . '.' . $request->file('image')->getClientOriginalExtension())
                ->toMediaCollection('image');
        }

        return response()->json([
            'status' => true,
            'message' => 'Family member updated successfully',
            'family_member' => new FamilyMemberResource($familyMember)
        ], 200);
    }

    public function destroy(FamilyMember $familyMember)
    {
        Gate::authorize('delete', $familyMember);

        if ($familyMember->chat) {
            foreach ($familyMember->chat->messages as $message) {
                $message->clearMediaCollection('image'); // Delete message images
            }
            $familyMember->chat->delete();
        }

        // Delete the family member
        $familyMember->clearMediaCollection('image');
        $familyMember->delete();

        return response()->json([
            'status' => true,
            'message' => 'Family member deleted successfully'
        ], 200);
    }

    public function message(Request $request, FamilyMember $familyMember)
    {
        Gate::authorize('view', $familyMember);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:9999',
            'sender' => 'required|in:ai,user',
            'image_text' => 'nullable|string|max:9999',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        // Check if a chat exists for this family member; create one if not
        $chat = $familyMember->chat ?? Chat::create([
            'user_id' => $user->id,
            'title' => $familyMember->name . "'s Chat",
            'family_member_id' => $familyMember->id
        ]);

        Gate::authorize('view', $chat);

        /** @var \App\Models\Message $message **/
        $message = $chat->messages()->create([
            'message' => $request->message,
            'sender' => $request->sender,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Message saved successfully',
            'chat' => new ChatResource($chat->load('messages')),
        ], 201);
    }
}
