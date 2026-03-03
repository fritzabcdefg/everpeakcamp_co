<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // generic show (used by admin/user list links as well as optional profile route)
        $user->load('orders', 'cartItems', 'reviews');
        return view('users.show', ['user' => $user]);
    }

    /**
     * Display the currently authenticated user's profile.
     */
    public function showProfile()
    {
        $user = Auth::user();

        // if profile data still missing, send them to the create page
        if (empty($user->phone) || empty($user->address)) {
            return redirect()->route('profile.create')
                         ->with('success', 'Please complete your profile before continuing.');
        }

        $user->load('orders', 'cartItems', 'reviews');
        return view('profile.index', ['user' => $user]);
    }

    /**
     * Show a form for the authenticated user to enter missing profile information.
     */
    public function createProfile()
    {
        return view('profile.create');
    }

    /**
     * Persist the profile information for the current user.
     */
    public function storeProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:1000',
            // add additional profile fields here if you extend the users table
        ]);

        $user->update($validated);

        return redirect()->route('home')->with('success', 'Profile saved successfully!');
    }

    /**
     * Show the form for editing the authenticated user's profile.
     */
    public function editProfile()
    {
        return view('profile.edit');
    }

    /**
     * Update profile for the authenticated user. Wraps update() to avoid needing a route model.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:1000',
        ]);

        $user->update($validated);
        return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('users.show', $user)->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Show the registration form.
     */
    public function createRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function storeRegister(Request $request)
    {
        // only email/password during initial registration
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
        ];

        // create user with empty profile values
        $user = User::create($userData);

        // log them in so they can complete profile
        Auth::login($user);

        // redirect to profile creation page immediately
        return redirect()->route('profile.create')
                         ->with('success', 'Registration successful! Please complete your profile.');
    }

    /**
     * Show the login form.
     */
    public function createLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Welcome Admin! Login successful!');
            }

            // normal customer: check profile completeness
            $user = Auth::user();
            if (empty($user->phone) || empty($user->address) || empty($user->name)) {
                return redirect()->route('profile.create')
                             ->with('success', 'Please complete your profile before placing orders.');
            }

            return redirect()->route('home')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}
