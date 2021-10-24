<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controlleraco instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request) {

        // Validate passed parameters
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Get the user with the email
        $user = User::where('email', $request['email'])->first();

        // check is user exist
        if (!isset($user)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User does not exist with this details'
                ],
                401
            );
        }

        // confirm that the password matches
        if (!Hash::check($request['password'], $user['password'])) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Incorrect user credentials'
                ],
                401
            );
        }

        // Generate Token
        $token = $user->createToken('AuthToken')->accessToken;

        // Add Generated token to user column
        User::where('email', $request['email'])->update(array('api_token' => $token));

        return response()->json(
            [
                'status' => true,
                'message' => 'User login successfully',
                'data' => [
                    'user' => $user,
                    'api_token' => $token
                ]
            ]
        );
    }

    public function profile() {

        $user = Auth::user();

        return response()->json(
            [
                'status' => true,
                'message' => 'User profile',
                'data' => $user
            ]
        );
    }

    public function all()
    {

        $users = User::all();

        return response()->json(
            [
                'status' => true,
                'message' => 'All users',
                'data' => $users
            ]
        );
    }
}
