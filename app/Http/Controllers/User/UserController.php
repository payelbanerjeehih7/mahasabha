<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function signup()
    {
        return view('signup');
    }
    public function submit(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/',
            'password'=>'required',
            'phone'=>'required|digits:10',
            'dob'=>'required',
            'gender'=>'required',
            'address'=>'required',
            'city'=>'required',
            'state'=>'required',
        ]);
        $name=$request->input('name');
        $email=$request->input('email');
        $password=md5($request->input('password'));
        $phone=$request->input('phone');
        $dob=$request->input('dob');
        $gender=$request->input('gender');
        $address=$request->input('address');
        $city=$request->input('city');
        $state=$request->input('state');
        $dom=$request->input('dom');
        $user=DB::table('users')->where('email','=',$email)->get();
        if(empty($user[0]))
        {
            if($request->file('file')){
            $file=$request->file('file');
            $filename=time()."_".$file->getClientOriginalName();
            $uploadlocation="./upload";
            $file->move($uploadlocation,$filename);
            
                $data=[
                    'name'=>$name,
                    'email'=>$email,
                    'password'=>$password,
                    'phone'=>$phone,
                    'dob'=>$dob,
                    'gender'=>$gender,
                    'address'=>$address,
                    'city'=>$city,
                    'state'=>$state,
                    'marriageDate'=>$dom,
                    'profilePicture'=>$uploadlocation.'/'.$filename,
                ];
                DB::table('users')->insert($data);
                return response()->json(['status' => 'success', 'message' => 'Inserted data successfully']);
            }else{
                $data=[
                    'name'=>$name,
                    'email'=>$email,
                    'password'=>$password,
                    'phone'=>$phone,
                    'dob'=>$dob,
                    'gender'=>$gender,
                    'address'=>$address,
                    'city'=>$city,
                    'state'=>$state,
                    'marriageDate'=>$dom,
                    'profilePicture'=>'NULL',
                ];
                DB::table('users')->insert($data);
                return response()->json(['status' => 'success', 'message' => 'Inserted data successfully']);
            }
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => 'Email already exists,Please Login']);
        }
    }
    public function index()
    {
        return view('index');
    }
    public function login()
    {
        return view('login');
    }
    public function save(Request $request)
    {
        $request->validate([
            'email' => 'required|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/',
            'password' => 'required',

        ]);

        $email = $request->input('email');
        $password = md5($request->input('password'));
        $user = DB::table("users")->where('email','=', $email)->get();
        
        if (empty($user[0])) {
            return response()->json(['status' => 'error', 'message' => 'User not found']);
        } 
        else if ($user[0]['password']!=$password) {
            return response()->json(['status' => 'error', 'message' => 'Password is incorrect']);
        } 
        else {
            $otp = rand(100000, 999999);
            $var = ['otp' => $otp];
            DB::table('users')->where('email', '=', $email)->update($var);
            $getDataOtp = DB::table('users')->where('email', '=', $email)->get();
            return response($getDataOtp);
            
        }
    }
    public function verifyAndLogin(Request $request){
        
        $email=$request->input('email');
        $password=md5($request->input('password'));
        $otp = $request->input('otp');
        $user = DB::table('users')->where('email','=',$email)->get();
        // console.log($userDetails);
        if (empty($otp)) {
            return response()->json(['status' => 'error', 'message' => 'Please Enter Your One Time Password'], 404);
        }
        else if($user[0]['otp']!=$otp)
        {
            return response()->json(['status' => 'error', 'message' => 'Wrong OTP'], 404);
        }
        else{
            // return response()->json(['status' => 'success', 'message' => 'Form submitted successfully']);
            Session::put('user', $user);
            session(['user_id' => $user[0]['_id']]);
                    if (Session::has('user_id')) {
                        // Retrieve the value of the session variable
                        $userId = Session::get('user_id');
                        // Use or display the value
                        // session(['user_id' => $user[0]->id]);
                        return response()->json(['status' => 'success', 'user' => $user]);
                        
                    } else {
                        echo "User ID session variable not set";
                    }
            
        }
    }
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        return redirect('/login')->with('message','Logout successfully');
    }

    public function showChangePasswordForm()
    {
        if (Session::has('user_id')) {
            $userId = Session::get('user_id');
            // Load the change password form
            return view('changepassword', ['userId' => $userId]);
        } else {
            return redirect('/login')->with('error', 'You must be logged in to change your password');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        if (Session::has('user_id')) {
            $userId = Session::get('user_id');
            $password = md5($request->input('password'));

            DB::table('users')
                ->where('_id', $userId)
                ->update(['password' => $password]);

            return response()->json(['status' => 'success', 'message' => 'Password changed successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Session expired. Please login again.'], 403);
        }
    }

}
