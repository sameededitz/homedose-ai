<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class FamilyMemberController extends Controller
{
    public function members()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $familyMembers = $user->familyMembers()->get();

        return response()->json([
            'status' => true,
            'family_members' => $familyMembers
        ], 200);
    }

    public function show(FamilyMember $familyMember)
    {
        Gate::authorize('view', $familyMember);

        return response()->json([
            'status' => true,
            'family_member' => $familyMember
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'relationship' => 'required|string|max:255',
            'message' => 'nullable|string',
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
            'family_member' => $familyMember
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
            'family_member' => $familyMember
        ], 200);
    }

    public function destroy(FamilyMember $familyMember)
    {
        Gate::authorize('delete', $familyMember);

        $familyMember->clearMediaCollection('image');
        $familyMember->delete();

        return response()->json([
            'status' => true,
            'message' => 'Family member deleted successfully'
        ], 200);
    }
}
