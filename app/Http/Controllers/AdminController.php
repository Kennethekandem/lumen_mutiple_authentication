<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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

        // Get the admin with the email
        $admin = Admin::where('email', $request['email'])->first();

        // check is user exist
        if (!isset($admin)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User does not exist with this details'
                ],
                401
            );
        }

        // confirm that the password matches
        if (!Hash::check($request['password'], $admin['password'])) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Incorrect user credentials'
                ],
                401
            );
        }

        // Generate Token
        $token = $admin->createToken('AdminAuthToken')->accessToken;

        // Add Generated token to user column
        Admin::where('email', $request['email'])->update(array('api_token' => $token));

        return response()->json(
            [
                'status' => true,
                'message' => 'Admin login successfully',
                'data' => [
                    'admin' => $admin,
                    'api_token' => $token
                ]
            ]
        );
    }
}
