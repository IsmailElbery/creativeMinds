<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    //get dashboard
    public function dashboard()
    {
        return view('dashboard');
    }
    //get login
    public function loginForm()
    {
        return view('login');
    }

    //get register
    public function registerForm()
    {
        return view('register');
    }

    //get activate
    public function activateForm()
    {
        return view('activate');
    }
    //login user
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
                //check if the credentials are correct return redirect to the dashboard
                if (Auth::attempt($credentials)) {
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->back()->with('error', 'Invalid Password');
                }
            } else {
                return redirect()->back()->with('error', 'Your account is not active');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    //register user
    public function register(RegisterRequest $request)
    {

        //create the user
        $user = new User();
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->save();
        //send the activation code
        $this->sendActivationCode($user);
        //return the user to the activation page
        return view('activate', ['mobile' => $user->mobile])->with('success', 'Activation code sent successfully to 01125570513');
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
        $mobile = '+201125570513';
        $client = new Client($sid, $token);

        $client->messages->create(
            $mobile,
            [
                'from' => $twilioNumber,
                'body' => 'Your activation code is: ' . $code,
            ]
        );
    }

    //activate user
    public function activate(ActivateRequest $request)
    {
        $user = User::where('activation_code', $request->activation_code)->where('mobile', $request->mobile)->first();
        //if the user exists
        if ($user) {
            //activate the user
            $user->active = 1;
            $user->save();
            //redirect to the login page
            return redirect()->route('login')->with('success', 'Your account has been activated successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid activation code');
        }
    }
    //logout user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
