<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //get user by token
    public function getUser()
    {
        return $this->response(auth()->user());
    }
}
