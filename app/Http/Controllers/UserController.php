<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'         => 'required|string|max:255',
            'lastname'          => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:User',
            'password'          => 'required|string|min:6',
            'password_verify'   => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 0,
                'message'   => $validator->errors()
            ]);
        }

        $user = new User();
        $user->firstname        = $request->firstname;
        $user->lastname         = $request->lastname;
        $user->email            = $request->email;
        $user->password         = Hash::make($request->password);
        $user->password_verify  = Hash::make($request->password_verify);
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'    => '1',
            'message'   => 'User berhasil ditambahkan!'
        ], 201);
    }

    
    public function login(Request $request){
        $credentials = $request->only('email','password');

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'logged'    => false,
                    'message'   =>'Invalid email and password'
                ]);
            }
        } catch(JWTException $e){
            return response()->json([
                'logged'    => false,
                'messgae'   => 'Generate Token Failed'
            ]);
        }

        return response()->json([
            "logged"    => true,
            "token"     => $token,
            "message"   => 'Login berhasil'
        ]);
    }
    

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                "logged"    => false,
                "message"   => 'Logout berhasil'
            ], 201);
        } else {
            return response()->json([
                "logged"    => true,
                "message"   => 'Logout gagal'
            ], 201);
        }
    }
}
