<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyUserRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserVerify;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd('gdfgfgdg');
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_no' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public function register(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_no' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' =>$request->mobile_no,
        ]);

        // Create userverfication link using unique token that can be identified using verification link
        $token = Str::random(64);
        UserVerify::create(['user_id' => $user->id, 'token' => $token]);
        $verficationUrl = config('app.url').'/account/verify/'.$token;


        // Generate An OTP
        $userOtp = $this->generateOtp($request->mobile_no);
        //  we can write here logic to send otp to users mobile number

        dd($verficationUrl,$userOtp,'dfgdfgfd');

        return redirect()->route('login')->with('success', 'Your have subscribed to plan successfully.');
    }



    public function generateOtp($mobileNumber) 
    {

        $user = User::where('mobile_no', $mobileNumber)->first();

        // User Does not Have Any Existing OTP

         $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
         $now = now();

         if($userOtp && $now->isBefore($userOtp->expire_at)){
            return $userOtp;
        }

        // Create a New OTP

         return UserOtp::create([
            'user_id' => $user->id,
            'otp' => 1234,   // Rigt now we have set it  to 1234, we can generate random using rand(123456, 999999)
            'expire_at' => $now->addMinutes(10)

        ]);
    }



    public function verifyAccount($token)
    {
        return view('users.verify', compact('token'));
    }
    public function verifyUser(VerifyUserRequest $request)
    {
// dd(request()->all(),'dfgfd');
        $verifyUser = UserVerify::where(['token'=> request('token'), 'token_expired' => false])->first();
        $message = 'Sorry your email cannot be identified.';
        if(!$verifyUser) {
            return redirect()->route('login')->with('error', $message);
        }
  
        if(!is_null($verifyUser) ){
            $userOtp = UserOtp::where('otp',request('otp'))->where('user_id',$verifyUser->user_id)->first();

            $now = now();
            if (!$userOtp) {
                return redirect()->back()->with('error', 'Your OTP is not correct');
            }else if($userOtp && $now->isAfter($userOtp->expire_at)){
                return redirect()->route('login')->with('error', 'Your OTP has been expired');
            }


           $user = $verifyUser->user;

            if($user){
              
                $userOtp->update(['expire_at' => now() ]);

                if(!$user->email_verified_at) {
                    $verifyUser->token_expired = 1;
                    $verifyUser->save();
                    $verifyUser->user->status = 1;
                    $verifyUser->user->save();
                } 
                Auth::login($user);
      
                return redirect('/home');
            }
              
        }
  
      return redirect()->route('login')->with('message', $message);
    }
}
