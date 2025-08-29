<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string',
        ]);
        
        $phoneNumber = preg_replace('/[^0-9+]/', '', $request->phone_number);
        
        // Check if user exists
        $user = User::where('phone_number', $phoneNumber)->first();
        
        if (!$user) {
            // Create new user with phone number
            $user = User::create([
                'phone_number' => $phoneNumber,
                'password' => Hash::make($request->password),
            ]);
        } else {
            // For existing users, validate password if they have one
            if ($user->password && !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'phone_number' => ['Invalid credentials.'],
                ]);
            }
            
            // Update password if not set
            if (!$user->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }
        }
        
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
