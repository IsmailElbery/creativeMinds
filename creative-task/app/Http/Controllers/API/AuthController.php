<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Web\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    //constructor
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register', 'activate']);
    }

    public function login(LoginRequest $request)
    {
        //check if the user exists
        $user = User::where('mobile', $request->mobile)->first();
        //if the user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            //check if the user is active
            if ($user->active) {
                $credentials = [
                    'mobile' => $request->mobile,
                    'password' => $request->password,
                ];
                $token = auth('api')->attempt($credentials);

                //return the token as response
                return $this->response([
                    'token' => $token
                ], 200, [], 1);
            } else {
                //return error message
                return $this->response([], 401, ['Please activate your account'], 0);
            }
        } else {
            //return error message
            return $this->response([], 401, ['Invalid mobile number or password'], 0);
        }
    }

    public function register(RegisterRequest $request)
    {
        //create a new user
        $user = new User();
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->save();

        //send activation code to the user
        $this->sendActivationCode($user);

        //return success message
        return $this->response([], 200, ['User created successfully'], 0);
    }

    public function sendActivationCode(User $user)
    {
        //generate a random code
        $code = rand(1000, 9999);

        //update the user with the new code
        $user->activation_code = $code;
        $user->save();

        //send the code to the user
        $this->sendSMS($user->mobile, $code);
    }

    public function sendSMS($mobile, $code)
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_NUMBER');

        //add the country code to the mobile number
        $mobile = '+2' . $mobile;
        $client = new Client($sid, $token);

        $client->messages->create(
            $mobile,
            [
                'from' => $twilioNumber,
                'body' => 'Your activation code is: ' . $code,
            ]
        );
    }

    //activate the user
    public function activate(ActivateRequest $request)
    {
        //check if the user exists

        $user = User::where('mobile', $request->mobile)->first();

        //if the user exists
        if ($user) {
            //check if the code is correct
            if ($user->activation_code == $request->code) {
                $user->active = 1;
                $user->save();
                return $this->response([], 200, ['User activated successfully'], 0);
            } else {
                //return error message
                return $this->response([], 401, ['Invalid activation code'], 0);
            }
        } else {
            //return error message
            return $this->response([], 401, ['Invalid mobile number'], 0);
        }
    }

    public function logout(Request $request)
    {
        //delete the token from the database
        $request->user()->currentAccessToken()->delete();

        //return success message
        return $this->response([], 200, ['User logged out successfully'], 0);
    }

    public function user()
    {
        //return the authenticated user
        return $this->response(auth()->user(), 200, [], 1);
    }
}
