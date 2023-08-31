<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\HttpResponse\HTTPResponse;
use App\Models\User;
use App\SecurityChecker\Checker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use Checker;
    use HTTPResponse;
    public function login(LoginRequest $request){
        if ($request->except(['email' , 'password'])){
            return $this->error("you're trying to pass extra attributes that not required and this not allowed for security reasons" , 422);
        }
        if ($this->checker()){
            return $this->error('query parameters are not allowed in this api');
        }
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $userAuth = auth()->user();
        return $this->success([
           "token" =>  $user->createToken("API TOKEN")->plainTextToken,
            "user" => UserResource::make($userAuth)
        ]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return $this->success(null , 'you have been logout successfully and your token has been deleted');
    }
}
