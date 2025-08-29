<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }
    
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required|string',
            'school' => 'required|string|max:255',
            'matriculation_number' => 'required|string|max:50',
            'state_of_origin' => 'required|string|max:100',
            'lga' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:18 years ago',
            'gender' => 'required|in:male,female,other',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:15',
            'account_name' => 'required|string|max:100',
            'passport_photo' => 'nullable|image|max:1024|mimes:jpg,jpeg,png',
        ]);
        
        $data = $request->except(['passport_photo']);
        
        // Handle passport photo upload
        if ($request->hasFile('passport_photo')) {
            // Delete old photo
            if ($user->passport_photo) {
                Storage::delete($user->passport_photo);
            }
            
            $data['passport_photo'] = $request->file('passport_photo')
                ->store('passport_photos', 'public');
        }
        
        // Mark profile as completed
        $data['profile_completion_status'] = 'completed';
        $data['profile_completed_at'] = now();
        
        $user->update($data);
        
        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
