<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', // Only allows @gmail.com emails
            ],
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',  // Add confirmed validation
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
    
            // Dispatch Registered event
            event(new Registered($user));
    
            return redirect()->route('verification.notice');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string',
        ]);
    
        try {
            // Check if the input is an email or a phone number
            $user = User::where('phone_number', $request->email_or_phone)
                        ->orWhere('email', $request->email_or_phone)
                        ->first();
    
            if ($user && Hash::check($request->password, $user->password)) {
                if (!$user->hasVerifiedEmail()) {
                    return back()->withErrors(['verification_error' => 'Please verify your email address before logging in.']);
                }
    
                auth()->login($user);
    
                // Log user authentication (debugging)
                \Log::info('User logged in:', ['user_id' => $user->id]);
    
                // Redirect to the dashboard
                return redirect()->route('dashboard');
            }
    
            return back()->withErrors(['login_error' => 'Invalid email/phone number or password.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
        // Verify Email Notice Handler
        public function verifyEmailNotice()
        {
            return view('auth.verify-email');
        }
    
        // Email Verification Handler
        public function verifyEmailHandler(EmailVerificationRequest $request)
        {
            $request->fulfill();
    
            return redirect()->route('dashboard');
        }
    
        // Resending the Verification Email Handler
        public function verifyEmailResend(Request $request)
        {
            $request->user()->sendEmailVerificationNotification();
    
            return back()->with('message', 'Verification link sent!');
        }
    
    

    public function dashboard()
{
    // You can pass any data to the dashboard view if needed
    return view('dashboard');
}


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'You have been logged out.']);
    }
}
