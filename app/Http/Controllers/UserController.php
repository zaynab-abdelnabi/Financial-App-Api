<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use JWTAuth;
// use Tymon\JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;




class UserController extends Controller
{

    //returns all Users
    public function getAll()
    {
        $User =  User::all();

        if (count($User) > 0) {
            $respond = [
                'status' => 200,
                'message' => 'All Users',
                'data' => $User,
            ];
            return $respond;
        } else {
            $respond = [
                'status' => 401,
                'message' => 'No Users found',
                'data' => [],
            ];
            return $respond;
        }
    }

    //returns a single User based on the given id 
    public function getOne($id)
    {
        $User = User::find($id);

        if ($User && is_string($User) === false) {
            $respond = [
                'status' => 200,
                'message' => 'User found',
                'data' => $User,
            ];
            return $respond;
        } else {
            $respond = [
                'status' => 401,
                'message' => 'User not found',
                'data' => null,
            ];
            return $respond;
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors()->toJson(), 400);
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];

            return response($respond);
        } else {

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $respond = [
                "status" => 200,
                "message" => "added successfully",
                "data" => $user
            ];
            return response($respond);
        }



        // $token = JWTAuth::fromUser($user);
        // return response()->json(compact('user', 'token'), 201);
        // return response($respond);
    }

    //Create new User
    // public function createToken(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }
    //     $user = User::create([
    //         'name' => $request->get('name'),
    //         'email' => $request->get('email'),
    //         'password' => Hash::make($request->get('password')),
    //     ]);
    //     $token = JWTAuth::fromUser($user);
    //     return response()->json(compact('user', 'token'), 201);
    // }


    //Edit an User
    public function editUser(Request $request, $id)
    {
        $User = User::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'email' => 'required|max:30',
            // 'password' => 'required|min:8|max:20',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];

            return $respond;
        }

        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = $request->password;
        $User->save();
        $respond = [
            'status' => 200,
            'message' =>  "successfully updated",
            'data' => $User,
        ];

        return $respond;
    }

    //Get an User by name or email
    public function getOneByName($name)
    {
        $name =  User::where('name', 'like', $name)->orwhere('email', 'like', $name)->first();

        if ($name) {
            $respond = [
                'status' => 200,
                'message' => 'Found',
                'data' => $name,
            ];
            return $respond;
        } else {
            $respond = [
                'status' => 404,
                'message' => 'Not found',
                'data' => null,
            ];
            return $respond;
        }
    }

    //Delete an User
    public function delete($id)
    {
        $User = User::find($id);
        if (isset($User)) {
            $User->delete();
            $respond = [
                'status' => 200,
                'message' => 'User deleted',
                'data' => null,
            ];
            return $respond;
        } else {
            $respond = [
                'status' => 401,
                'message' => 'User not found',
                'data' => null,
            ];
            return $respond;
        }
    }

    //edit password of an User
    public function editAdminPassword(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $respond = [
                    'status' => 400,
                    'message' => 'incorrect email or password',
                    'data' => $credentials,
                ];
                return $respond;
            }
        } catch (JWTException $e) {
            $respond = [
                'status' => 500,
                'message' => 'couldn\'t authenticate user',
                'data' => null,
            ];
            return $respond;
        }

        $User = User::where('email', $request->email)->first();
        
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:25',
            'email' => 'required|max:30',
            'password' => 'required|min:8|max:20',
            'newPassword' => 'required|min:8|max:20',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' =>  $validator->errors()->first(),
                'data' => null,
            ];

            return $respond;
        }

        $User->password = Hash::make($request->newPassword);
        $User->save();

        $respond = [
            'status' => 200,
            'message' =>  "user password updated successfully",
            'data' => $User,
        ];

        return $respond;
    }

    public function editPassword(Request $request, $id)
    {
        $User = User::find($id);
        $User->password = $request->password;

        if ($User) {
            if ($User->password && strlen($User->password) <= 20) {
                $User->save();
                $respond = [
                    'status' => 200,
                    'message' => 'Password updated',
                    'data' => $User,
                ];
                return $respond;
            } else {
                $respond = [
                    'status' => 401,
                    'message' => 'Password not updated',
                    'data' => null,
                ];
                return $respond;
            }
        } else {
            $respond = [
                'status' => 401,
                'message' => 'User not found',
                'data' => null,
            ];
            return $respond;
        }
    }

    //login an User
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $respond = [
                    'status' => 400,
                    'message' => 'incorrect email or password',
                    'data' => null,
                ];
                return $respond;
            }
        } catch (JWTException $e) {
            $respond = [
                'status' => 500,
                'message' => 'can\'t login',
                'data' => null,
            ];
            return $respond;
        }
        $admin = User::where('email', $request->email)->first();
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => 'Login Successfully',
            'status' => 200,
            'admin' => $admin,
            'data' => response()->json(compact('token')),
        ]);
    }
    //Logout
    public function logout()
    {
        Auth::logout();

        if (Auth::check())
            $msg = "The user is still logged in";
        else
            $msg = "The user is successfully logged out";

        return response()->json(['message' => $msg]);
    }
}
