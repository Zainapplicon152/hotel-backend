<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() ,[
            'email' => 'required|unique:users',
            'password' => 'required',
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()] , 409);
        }

        $u = new User();
        $u->name = $request->name;
        $u->email = $request->email;
        $u->password = encrypt($request->password);
        $u->save();

        return response()->json(['message' => 'Register successfully'] , 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all() ,[
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()] , 409);
        }

        $user = User::where('email' , $request->email)->first();
        $password = decrypt($user->password);
        if($user && $password == $request->password){
            return response()->json(['user' => $user , 'message' => 'Login successfully'] , 200);
        }else{
            return response()->json(['error' =>["Login failed!"]] , 409);
        }
    }
}
