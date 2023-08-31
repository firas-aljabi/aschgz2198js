<?php

namespace App\SecurityChecker;

trait Checker
{
    public function checker(){
        if(request()->query()){
            return true;
        }
        return false;
    }
}
