<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function response($data = [], $status = 200, $messages = [], $length = '' ){

        return response([
            'data' => $data,
            'status' => $status,
            'messages' => $messages,
            'dataLength' => $length
        ], 200);
    }

}
