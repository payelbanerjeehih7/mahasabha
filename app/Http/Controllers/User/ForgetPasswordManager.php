<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;


class ForgetPasswordManager extends Controller
{
    public function forgetPassword()
    {
        return view('forget-password');
    }
    public function forgetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => "required|email|exists:users"
        ]);
        $token=Str::random(length:64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at'=> Carbon::now()
            ]);
            Mail::send("emails.reset-password", ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("Reset Password");
            });

            // Store the email in the session
            $request->session()->put('reset_password_email', $request->email);

            return redirect()->to(route("forget.password"))->with("message", "We have send an email to reset password.");
    }
    public function resetPassword($token,Request $request) {
        // Retrieve the email from the session
        $email = $request->session()->get('reset_password_email');
        return view('new-password',compact('token', 'email'));
    }
    public function resetPasswordPost(Request $request) {
        $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required|string|min:6|confirmed",
            "password_confirmation" => "required"
            ]);
            $updatePassword = DB::table(table:'password_resets')->where([
            "email" =>$request->email,
            "token" => $request->token
            ])->first();
            if (!$updatePassword) {
            return redirect()->to (route("reset.password"))->with("message", "Invalid");
            }
            $newpassword=md5($request->password);
            DB::table('users')->where("email", $request->email)->update(["password" => $newpassword]);
            DB::table("password_resets")->where(["email" => $request->email])->delete();
            return redirect()->to (route("login"))->with("message", "Password reset success");
    }
}
