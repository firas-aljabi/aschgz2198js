<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInformationRequest;
use App\Http\Requests\UpdateUserInformationRequest;
use App\Http\Resources\UserInformationResource;
use App\Http\Resources\UserResource;
use App\HttpResponse\HTTPResponse;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\UserLink;
use App\SecurityChecker\Checker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

class UserInformationController extends Controller
{
    use Checker;
    use HTTPResponse;
    public function index(){
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        $usersInformation = UserInformation::all();

        return UserInformationResource::collection($usersInformation);
    }

    public function store(StoreUserInformationRequest $request){
        if ($request->except
            (
                [
                    'name' ,
                    'email' ,
                    'image' ,
                    'linkedin' ,
                    'instagram' ,
                    'facebook' ,
                    'twitter' ,
                    'phone' ,
                    'location' ,
                    'aboutme' ,
                    'template'
                ]
            )
        ){
            return $this->error("you're trying to pass extra attributes that not required and this not allowed for security reasons" , 422);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }

        $request->validated($request->all());

        $userInformation = UserInformation::create(array_merge($request->all() , ['user_id' => Auth::user()->id]));
        $userLink = UserLink::create([
            'user_id' => Auth::user()->id,
            'link' => 'https://www.e_card/'.$userInformation->name . '?user_id='.Auth::user()->id.'?template_id='.$request->template
        ]);

        return $this->success($userInformation);
    }

    public function show(UserInformation $userInformation) {
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        return UserInformationResource::make($userInformation);
    }

    public function update(UpdateUserInformationRequest $request){
        $userInformation = UserInformation::where('user_id' , Auth::user()->id)->first();
        if(Auth::user()->id !== $userInformation->user_id && !Auth::user()->is_admin){
            $this->error('you are not authorize to make this request' , 401);
        }
        if ($request->except
        (
            [
                'name' ,
                'email' ,
                'image' ,
                'linkedin' ,
                'instagram' ,
                'facebook' ,
                'twitter' ,
                'phone' ,
                'location' ,
                'aboutme' ,
            ]
        )
        ){
            return $this->error("you're trying to pass extra attributes that not required and this not allowed for security reasons" , 422);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }

        $userInformation->update($request->all());

        return UserInformationResource::make($userInformation);
    }

    public function destroy(UserInformation $userInformation){
        if (!Auth::user()->is_admin){
            $this->error('you are not authorize to make this request' , 401);
        }
        if ($this->checker()){
            return $this->error('query parameter not allowed in this api' , 422);
        }
        $userInformation->delete();

        return $this->success($userInformation , 'deleted successfully');

    }

    public function hasRecord(){
        $userInformation = UserInformation::where('user_id' , Auth::user()->id)->first();
        if ($userInformation){
            return $this->success(['is_has' => true]);
        }
        return $this->success(['is_has' => false]);
    }
}
