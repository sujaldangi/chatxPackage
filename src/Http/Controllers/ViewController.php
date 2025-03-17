<?php

namespace Sujal\Chatx\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Import the Auth facade for user authentication
use Illuminate\Http\Request; // Import the Request class to handle HTTP requests

class ViewController extends Controller
{
    // This method handles the registration page view
    public function registerView(Request $request)
    {
        // Check if the user is already logged in
        if (Auth::check()) {
            // If the user is logged in, redirect them to the dashboard
            return redirect()->route('dashboard');
        }

        // If the user is not logged in, return the registration view and prevent caching
        return response()
            ->view('chat-package::auth.register') // Render the 'auth.register' view
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'); // Disable caching for the registration page
    }

    // This method handles the login page view
    public function loginView(Request $request)
    {
        // Check if the user is already logged in
        if (Auth::check()) {
            \Log::info(Auth::check()); // Log the authentication check result for debugging (logging if user is logged in)
            // If the user is logged in, redirect them to the dashboard
            return redirect()->route('dashboard');
        }

         

        // If the user is not logged in, return the login view and prevent caching
        return response()
            ->view('chat-package::auth.login') // Render the 'auth.login' view
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'); // Disable caching for the login page
    }

    // This method handles the dashboard view
    public function dashboardView(Request $request)
    {
        // Return the 'dashboard' view for authenticated users
        return view('chat-package::dashboard');
    }

    // This method handles the forgot password page view
    public function ForgetPasswordForm(Request $request)
    {
        // Return the 'auth.forgetPassword' view for the forgot password form
        return view('chat-package::auth.forgetPassword')->withErrors('');
    }

    // This method handles the reset password form page view
    // It takes a token as a parameter for validating the password reset request
    public function ResetPasswordForm($token)
    {
        // Return the 'auth.forgetPasswordLink' view and pass the token to it
        return view('chat-package::auth.forgetPasswordLink', ['token' => $token]);
    }
}
