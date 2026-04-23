<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Portal'; 


public function username()
{
    $login = request()->input('login');

    // If it looks like an email, return 'email'. Otherwise, return 'phone_number'.
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

    // We manually add the detected field to the request so the trait finds it
    request()->merge([$field => $login]);

    return $field;
}


public function showLoginForm()
{
    return view('auth.auth-signin');
}




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
     * Handle the logout request and redirect to the login page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');  // Redirect to login page after logout
    }
}
