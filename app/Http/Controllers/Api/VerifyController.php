<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class VerifyController extends Controller
{
    public function resend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
            'status' => false,
            'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
            'status' => false,
            'message' => 'User not found'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
            'status' => true,
            'message' => 'Email already Verified'
            ], 200);
        }

        SendEmailVerification::dispatch($user)->delay(now()->addSeconds(5));

        return response()->json([
            'status' => true,
            'message' => 'A new verification link has been sent to the email address you provided during registration.'
        ], 200);
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent. Please check your email.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => __($status)
            ], 400);
        }
    }
}
