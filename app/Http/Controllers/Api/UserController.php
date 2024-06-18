<?php

namespace App\Http\Controllers\Api;

use App\Enum\UserType;
use App\Http\Controllers\Controller;
use App\Http\Resources\RelationshipsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function relate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'related_user_id' => 'required|integer|exists:users,id',
            'type' => ['required', Rule::enum(UserType::class)],
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);

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

//        return $users->limit(2)->get();


        $users = $users->paginate($request->paginate ?? 20);

        return RelationshipsCollection::make($users);
    }
}
