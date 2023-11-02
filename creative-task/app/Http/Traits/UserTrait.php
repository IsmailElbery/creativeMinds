<?php

namespace App\Traits;

use App\Models\User;
use Twilio\Rest\Client;

class UserTrait
{

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
}
