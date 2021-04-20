<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
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
	protected $redirectTo = '/login';
	private $user;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->user = null;
		$this->middleware('guest')->except('logout');
	}

	/**
	 * Credentials
	 * Added to overwrite the login credentials
	 */
	protected function credentials(Request $request)
	{
		return [
			'login' => request()->login,
			'password' => request()->password,
			'active' => 1
		];
	}

	/**
	 * Login
	 */
	public function login(Request $request) {
		// If method is post create a session
		$credentials = $this->credentials($request);
		if(Auth::attempt($credentials)) {
			$this->user = Auth::user();
			return redirect()->route('dashboard');
		}
		elseif($request->isMethod('post')) {
			return view('auth.login')->withErrors([
				'login' => 'Nom d\'utilisateur erroné ou manquant.',
				'password' => 'Mot de passe incorrect',
			]);
		}
		return view('auth.login');
	}

	/**
	 * Logout
	 */
	public function logout() {
		// Delete the user
		Auth::logout();
		// Redirect to login page
		return redirect()->route('login');
	}
}
