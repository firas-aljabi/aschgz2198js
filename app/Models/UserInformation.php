<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'image' . '.' . $image->extension();
        $image->move(public_path('users_images') , $newImageName);
        return $this->attributes['image'] =  '/'.'users_images'.'/' . $newImageName;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
