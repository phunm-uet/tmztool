<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Department;
use App\UserManager;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Login and redirect user
     *
     * @return Redirect
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember');
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $user = Auth::user();
            $user_id = $user->id;
            $department_id = $user->department_id;
            $department = Department::find($department_id);
            $department_name = $department->name;
            $slug = $department->slug;
            if(UserManager::where('user_id',$user_id)->first() && $department_id != '1') {
                return redirect('/'.$slug.'leader');
            } else {
                return redirect("/".$slug)->header("Authorization","Bearer ".Auth::user()->api_token);
            }
        } else {
            return redirect('/login')->with('status', 'Wrong email or password!');
        }
    }
}
