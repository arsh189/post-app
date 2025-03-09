<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister()
    {
        $roles = Role::all(); // Fetch all roles
        return view('auth.register', compact('roles'));
    }

    // Handle User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign selected role
        $user->assignRole($request->role);

        Auth::login($user);
        return redirect('/dashboard');
    }

    // Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login_old(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $email = $request->email;
        $key = 'login_attempts:' . $email;

        // Check if the user is rate-limited
        if (RateLimiter::tooManyAttempts($key, 5)) { // Allow max 5 attempts
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Too many login attempts. Try again in $seconds seconds."])->withInput();
        }

        $remember = $request->has('remember'); // Check if Remember Me is checked

        if (Auth::attempt(['email' => $email, 'password' => $request->password], $remember)) {
            RateLimiter::clear($key); // Reset attempts after successful login
            return redirect()->intended('dashboard'); // Redirect after successful login
        }

        // Increment rate limiter for failed login
        RateLimiter::hit($key, 60); // Block for 60 seconds if max attempts exceeded

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }


    // Logout
    public function forgetPassword(){

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
