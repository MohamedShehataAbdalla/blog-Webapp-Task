<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUser;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    /**
     * Login a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password ]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('MohamedShehataAdvancedSoftware')->accessToken;
            $success['first_name'] = $user->first_name;

            return $this->sendResponse($success, 'User Login Successfully' );
        }else {
            return $this->sendError('Please Chack your Auth'  ,['error' => 'Unauthorised'] );
        }


    }
}
