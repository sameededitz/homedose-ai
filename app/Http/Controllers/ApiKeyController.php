<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiKeyController extends Controller
{
    public function index()
    {
        $keys = ApiKey::all();
        return view('admin.all-keys', compact('keys'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'key' => 'required|unique:api_keys,key',
            'type' => 'required|in:openai,claude',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput()->with(['form' => 'error']);
        }

        ApiKey::create([
            'type' => $request->name,
            'key' => $request->key,
        ]);
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Key created successfully',
        ]);
    }

    public function activateKey($id)
    {
        ApiKey::setActiveKey($id);

        return redirect()->back()->with([
            'icon' => 'success',
            'message' => 'Model key activated successfully'
        ]);
    }

    public function destroy(ApiKey $key)
    {
        $activeKeyCount = ApiKey::where('type', $key->type)
            ->where('is_active', true)
            ->count();

        if ($key->is_active && $activeKeyCount <= 1) {
            // Cannot delete the only active key of this type
            return redirect()->back()->with([
                'status' => 'danger',
                'message' => 'Cannot delete the only active API key for this type.'
            ]);
        }

        $key->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Ai Key deleted successfully.']);
    }
}
