<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;


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
  protected $redirectTo = '/dashboard';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
    Session::put('backUrl', URL::previous());
  }
  public function showLoginForm()
  {
    return view('auth.login2');
  }

  public function vueLogin(Request $request)
  {
    $user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
    if($user){
      return response()->json([
        'status'   => 'success',
        'user' => Auth::user(),
        'error' => false,
        'url' => Session::get('backUrl') ? Session::get('backUrl') :   $this->redirectTo
      ]);
    } else {
      return response()->json([
        'status' => 'error',
        'user' => false,
        'error'   => 'Authentication failed: login credentials mismatch!',
        'url' => $this->redirectTo
      ]);
    }
  }





}
