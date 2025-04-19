<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => "sometimes|string|email|max:255|unique:users,email,{$user->id}",
            'password' => 'sometimes|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|string|max:255',
            'language' => 'nullable|string|max:5',
            'profile'  => 'nullable|array',
        ]);

        // Hash new password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Update user
        $user->update($data);

        return response()->json($user);
    }

    /**
     * Display a listing of all users (admin only).
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Display the specified user (admin only).
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update a user's details (admin only).
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function adminUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'email'      => "sometimes|string|email|max:255|unique:users,email,{$id}",
            'password'   => 'sometimes|string|min:8|confirmed',
            'role_id'    => 'sometimes|integer|exists:roles,id',
            'phone'      => 'nullable|string|max:20',
            'avatar'     => 'nullable|string|max:255',
            'language'   => 'nullable|string|max:5',
            'profile'    => 'nullable|array',
            'active'     => 'boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage (admin only).
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
