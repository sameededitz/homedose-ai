<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HelpRequestController extends Controller
{
    public function feedbacks()
    {
        $feedbacks = HelpRequest::latest()->get();

        return view('admin.all-help', compact('feedbacks'));
    }

    public function view(HelpRequest $feedback)
    {
        return response()->json([
            'message' => $feedback->message,
        ]);
    }

    public function destroy(HelpRequest $feedback)
    {
        $feedback->delete();

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Request deleted successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $feedback = HelpRequest::create($request->only([
            'email',
            'subject',
            'message',
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Help request submitted successfully!',
            'help_request' => $feedback
        ], 201);
    }
}
