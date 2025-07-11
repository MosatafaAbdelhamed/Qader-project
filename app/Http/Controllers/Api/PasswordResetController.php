<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;
use App\Models\Volunteer;
use App\Models\Organization;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordLink;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'user_type' => 'required|in:volunteer,organization',
    ]);
    $email = $request->email;
    $user_type = $request->user_type;

    $model = $request->user_type === 'volunteer' ? \App\Models\Volunteer::class : \App\Models\Organization::class;
    $user = $model::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $token = Str::random(64);
    $hashedToken = Hash::make($token);

    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    DB::table('password_reset_tokens')->insert([
        'email' => $request->email,
        'token' => $hashedToken,
        'created_at' => Carbon::now(),
    ]);

    return response()->json([
        'message' => 'Reset token generated successfully',
        'token' => $token,
        'user_type' => $user_type,
        'email' => $email,
        'reset_url' => url("/reset-password/{$user_type}/{$token}")
    ]);
}
    public function reset(Request $request, $user_type, $token)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHours(1))
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        if (!Hash::check($token, $resetRecord->token)) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $model = $user_type === 'volunteer' ? \App\Models\Volunteer::class : \App\Models\Organization::class;
        $user = $model::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $user->tokens()->delete();

        event(new PasswordReset($user));

        return response()->json(['message' => 'Password reset successfully']);
    }
}
