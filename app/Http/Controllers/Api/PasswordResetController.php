<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\ResetPasswordLink;

class PasswordResetController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');

    // }

    public function sendResetLinkEmail(LinkEmailRequest $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);


        $url = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(30),
            ['email' => $request->email]
        );


        $url = str_replace(env('APP_URL'), env('FRONTEND_URL'), $url);


        Mail::to($request->email)->send(new ResetPasswordLink($url));

        return response()->json([
            'message' => 'Reset Password link sent to your email'
        ]);

    }


    public function reset(ResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        if (!$user){
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password reset successfully'
        ],200);
    }

}





// $url = \URL::tenporarySignedRoute(name: 'password.reset', now()->addMinute(30),['email' => $request->email]);

//         ($url) = str_replace(env(key: 'APP_URL'), env(key: 'FRONTEND_URL'), $url);

//         // dd($url);

//         Mail::to($request->email)->send(new ResetPasswordLink());


//         return response()->json([
//             'message' => 'Reset Password link sent on your email'
//         ]);
