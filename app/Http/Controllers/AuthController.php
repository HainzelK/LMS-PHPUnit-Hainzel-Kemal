<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;
    public function register(Request $request)
    {
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // Use 'name' instead of 'username'
            'phone_number' => 'required|string|unique:users,phone_number', // Ensure it's string for compatibility
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Save the user in the database
            User::create([
                'name' => $request->name, // Updated to 'name'
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
            return 'Register successful';
            // return redirect()->route('login')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }


    // Fitur Login
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);
    
        try {
            $user = User::where('phone_number', $request->phone_number)->first();
    
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
    
                // Generate token for API authentication
                $token = $user->createToken('auth_token')->plainTextToken;
                session(['auth_token' => $token]);
    
                // Check roles by existence in admin or librarian tables
                if (\DB::table('admin')->where('user_id', $user->id)->exists()) {
                    // Redirect to the admin route
                    return redirect()->route('admin.librarians.index')->with('success', 'Login successful');
                } elseif (\DB::table('librarians')->where('user_id', $user->id)->exists()) {
                    // Redirect to the librarian route
                    return redirect()->route('books.index')->with('success', 'Login successful');
                }
    
                // Default fallback for unclassified users
                return redirect('/')->with('error', 'Unauthorized access.');
            }
    
            return back()->withErrors(['login_error' => 'Invalid phone number or password.'])->withInput();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors(['unexpected_error' => 'An unexpected error occurred.']);
        }
    }
    
        
        
    // Logout
    public function logout()
    {
        session()->forget('user');

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
