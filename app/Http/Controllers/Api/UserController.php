<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function user()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully!',
            'user' => new UserResource($user),
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|min:3',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:20420',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user **/
        $user->update([
            'name' => $request->username,
        ]);

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMedia($request->file('avatar'))
                ->usingFileName(time() . '_user_' . $user->id . '.' . $request->file('avatar')->getClientOriginalExtension())
                ->toMediaCollection('avatar');
        }

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully!',
            'user' => new UserResource($user),
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user **/

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => __('auth.password'),
            ], 403);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully!',
        ], 200);
    }
}
