<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:4'],
            'avatar' => ['nullable', 'image', 'max:2048'], // profile picture
        ]);

        // Prepare data
        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'student', // default role
            'password' => Hash::make($request->password),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
       // dd($data);
        // Create user
        User::create($data);
        

        // Redirect to login without auto-login
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
