<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);


        /** @var User $user */
        $user = User::where(['email' => $request->email])->first();

        if (!$user && Hash::check($request->password, $user->password))
            throw ValidationException::withMessages([
                'email' => 'The email not exists in the database',
            ]);

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }
}
