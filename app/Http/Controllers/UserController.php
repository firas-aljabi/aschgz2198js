<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\SecurityChecker\Checker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use Checker;
    public function index(){
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }

        $users = User::all();

        return UserResource::collection($users);
    }

    public function show(User $user) {
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        return UserResource::make($user);
    }

    public function store(StoreUserRequest $request){
        if ($request->except(['email' , 'password'])){
            return $this->error("you're trying to pass extra attributes that not required and this not allowed for security reasons" , 422);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        $request->validated($request->all());

        $user = User::create($request->all());

        return $this->success($user);
    }

    public function resetPassword(ResetPasswordRequest $request , User $user){
        if (!Auth::user()->is_admin && Auth::user()->id !== $user->id){
            return $this->error('you are not authorize to make this request' , 401);
        }
        if ($request->except(['password'])){
            return $this->error("you're trying to pass extra attributes that not required and this not allowed for security reasons" , 422);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        $request->validated($request->all());

        $user->update($request->all());

        return $this->success($user);

    }

    public function profile(){
        return UserResource::make(Auth::user());
    }

    public function destroy(User $user){
        if (!Auth::user()->is_admin){
            return $this->error('you are not authorize to make this request' , 401);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        $user->delete();

        return $this->success($user , 'deleted successfully');
    }
}
