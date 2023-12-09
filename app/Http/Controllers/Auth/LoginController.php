<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


      /**
     * Login store function.
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
       
        $user = User::where('email',request('email'))->where('status',true)->first();

        if($user) {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password'),'status' => true])) {

                return redirect()->route('users.index');
            } else {
                return redirect()->route('login')->with('error', 'Invalid credentials');
            }
          
        } else {
            return redirect()->route('login')->with('error', 'Invalid credentials');
        }
    }
}
