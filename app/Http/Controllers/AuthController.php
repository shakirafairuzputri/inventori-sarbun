<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function loginproses(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Extract credentials from the request
        $credentials = $request->only('email', 'password');
        Log::info('Attempting to authenticate with:', $credentials);

        // Retrieve the user by email
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status != 'Aktif') {
                Log::warning('Login attempt for inactive user: ' . $request->email);
                return redirect()->back()->withErrors(['email' => 'Your account is inactive. Please contact admin.'])->withInput();
            }
    
            // Check if the provided password matches the hashed password in the database
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user); // Log in the user
                Log::info('Manual login successful for email: ' . $request->email);

                // Redirect based on user role
                $role = $user->role;
                if ($role == 'admin') {
                    return redirect()->route('admin.dashboardo')->with('success', 'You have Logged in as Admin!');
                } 
                elseif ($role == 'supervisor') {
                    return redirect()->route('supervisor.dashboard')->with('success', 'You have Logged in as Supervisor!');
                }
                elseif($role == 'pegawai'){
                    return redirect()->route('pegawai.dashboardp')->with('success', 'You have Logged in as Pegawai!');
                }

                // Add handling for other roles if needed
            } else {
                Log::warning('Password check failed for email: ' . $request->email);
            }
        } else {
            Log::warning('User not found for email: ' . $request->email);
        }

        // Redirect back to login with an error message if login fails
        return redirect()->back()->withErrors(['email' => 'Email or Password is incorrect'])->withInput();
    }

    // Handle the logout request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have logged out!');
    }
}
