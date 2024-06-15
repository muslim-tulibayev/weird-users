<?php

namespace App\Http\Controllers\Api;

use App\Enum\UserType;
use App\Http\Controllers\Controller;
use App\Http\Resources\RelationshipsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => 'The provided credentials are incorrect.',
        ]);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function relate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'related_user_id' => 'required|integer|exists:users,id',
            'type' => ['required', Rule::enum(UserType::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();

        if ($user->relationshipsOfType($request->type)->exists())
            return response()->json([
                'success' => false,
                'message' => 'Related user data already exists.',
            ]);

        $user->relationships()->attach([$request->related_user_id => ['type' => $request->type]]);

        return response()->json([
            'success' => false,
            'message' => 'Related user data saved successfully.',
        ]);
    }

    public function relationships(Request $request)
    {
        $user = Auth::user();

        $users = $user->relationships()
            ->when($request->has('type'), fn($q) => $q
                ->wherePivot('type', $request->type));

        $users = $users->paginate($request->paginate ?? 20);

        return RelationshipsCollection::make($users);
    }
}
