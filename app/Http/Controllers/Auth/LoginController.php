<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard'; // Redirect all users to the dashboard

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine the guard and username field based on the identity
        $guard = $this->determineGuard($request->identity);
        $usernameField = $this->usernameField($guard);

        // Attempt to authenticate the user
        if (Auth::guard($guard)->attempt([$usernameField => $request->identity, 'password' => $request->password], $request->remember)) {
            // Authentication passed
            return $this->sendLoginResponse($request);
        }

        // Authentication failed
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Determine the guard based on the identity (email or login ID).
     *
     * @param  string  $identity
     * @return string
     */
    protected function determineGuard($identity)
    {
        // Check if the identity matches a student email pattern
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            // Check if the email exists in the students table
            if (\App\Models\StudentRecord::where('email', $identity)->exists()) {
                return 'student';
            }
        }

        // Check if the identity matches a parent email pattern
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            // Check if the email exists in the parents table
            if (\App\Models\ParentDetail::where('parent_email', $identity)->exists()) {
                return 'parent';
            }
        }

        // Default to the web guard (for regular users)
        return 'web';
    }

    /**
     * Get the username field based on the guard.
     *
     * @param  string  $guard
     * @return string
     */
    protected function usernameField($guard)
    {
        return match ($guard) {
            'student' => 'email',
            'parent' => 'parent_email',
            default => 'email', // Default for web guard
        };
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}