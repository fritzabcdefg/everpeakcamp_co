<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Mail\VerifyEmailMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Get users data for DataTables (API endpoint)
     */
    public function datatable(Request $request)
    {
        $query = User::query();

        return DataTables::of($query)
            ->addColumn('photo', function ($user) {
                return $user->photo 
                    ? '<img src="' . Storage::url($user->photo) . '" alt="' . $user->name . '" width="40" height="40" class="img-thumbnail rounded-circle">'
                    : '<span class="badge bg-secondary">No Photo</span>';
            })
            ->addColumn('role', function ($user) {
                if (auth()->check() && auth()->user()->role === 'admin' && auth()->user()->id !== $user->id) {
                    return '<form action="' . route('users.updateRole', $user) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('PUT') . '
                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                            <option value="customer" ' . ($user->role === 'customer' ? 'selected' : '') . '>Customer</option>
                            <option value="admin" ' . ($user->role === 'admin' ? 'selected' : '') . '>Admin</option>
                        </select>
                    </form>';
                } else {
                    return '<span class="badge bg-' . ($user->role === 'admin' ? 'danger' : 'primary') . '">' . ucfirst($user->role) . '</span>';
                }
            })
            ->addColumn('status', function ($user) {
                if (auth()->check() && auth()->user()->role === 'admin' && auth()->user()->id !== $user->id) {
                    return '<form action="' . route('users.updateStatus', $user) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('PUT') . '
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                            <option value="active" ' . ($user->status === 'active' ? 'selected' : '') . '>Active</option>
                            <option value="inactive" ' . ($user->status === 'inactive' ? 'selected' : '') . '>Inactive</option>
                        </select>
                    </form>';
                } else {
                    return '<span class="badge bg-' . ($user->status === 'active' ? 'success' : 'warning') . '">' . ucfirst($user->status) . '</span>';
                }
            })
            ->addColumn('created', function ($user) {
                return $user->created_at->format('M d, Y');
            })
            ->addColumn('actions', function ($user) {
                return $this->renderUserActions($user);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->rawColumns(['photo', 'role', 'status', 'actions'])
            ->make(true);
    }

    /**
     * Render action buttons for users
     */
    private function renderUserActions($user)
    {
        $actions = '<div class="btn-group btn-group-sm" role="group">';
        
        $actions .= '<a href="' . route('users.show', $user) . '" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>';
        
        if (auth()->check() && auth()->user()->role === 'admin' && auth()->user()->id !== $user->id) {
            $actions .= '<a href="' . route('users.edit', $user) . '" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            $actions .= '<button type="button" class="btn btn-danger" title="Delete" onclick="if(confirm(\'Are you sure?\')) { document.getElementById(\'delete-user-' . $user->id . '\').submit(); }"><i class="fas fa-trash"></i></button>';
        }
        
        $actions .= '</div>';

        if (auth()->check() && auth()->user()->role === 'admin' && auth()->user()->id !== $user->id) {
            $actions .= '<form id="delete-user-' . $user->id . '" action="' . route('users.destroy', $user) . '" method="POST" style="display:none;">';
            $actions .= '<input type="hidden" name="_method" value="DELETE">';
            $actions .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
            $actions .= '</form>';
        }

        return $actions;
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users/profiles', 'public');
        }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users/profiles', 'public');
        }

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
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'password' => 'required|string|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 3 characters long.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'name.regex' => 'Full name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 255 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'password.confirmed' => 'Passwords do not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.same' => 'Passwords do not match.',
            'photo.image' => 'Photo must be a valid image file.',
            'photo.mimes' => 'Photo must be JPG, PNG or GIF format.',
            'photo.max' => 'Photo cannot exceed 2MB.',
            'photo.dimensions' => 'Photo must be at least 100x100 pixels.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => null, // Not verified yet
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $userData['photo'] = $request->file('photo')->store('users/profiles', 'public');
        }

        // create user with profile data
        $user = User::create($userData);

        // Generate verification token
        $token = Str::random(64);
        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send verification email
        Mail::send(new VerifyEmailMail($user, $token));

        return redirect()->route('login')
                         ->with('success', 'Registration successful! A verification link has been sent to your email. Please check your inbox.');
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

        // First check if user exists and get their email verification status
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && is_null($user->email_verified_at)) {
            return back()->withErrors([
                'email' => 'Please verify your email before logging in. Check your inbox for the verification link.',
            ])->onlyInput('email');
        }

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

    /**
     * Verify user email with token.
     */
    public function verifyEmail($token)
    {
        $verificationToken = EmailVerificationToken::where('token', $token)->first();

        if (!$verificationToken) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        if (!$verificationToken->isValid()) {
            // Delete expired token
            $verificationToken->delete();
            return redirect()->route('login')->withErrors(['email' => 'Verification link has expired. Please register again or request a new verification link.']);
        }

        // Get user and mark email as verified
        $user = $verificationToken->user;
        $user->update(['email_verified_at' => now()]);

        // Delete the verification token
        $verificationToken->delete();

        // Refresh user data and log in
        $user = $user->fresh();
        Auth::login($user);

        return redirect()->route('profile.create')
                         ->with('success', 'Email verified successfully! Please complete your profile.');
    }

    /**
     * Resend verification email.
     */
    public function resendVerification(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Only allow resend for unverified users
        if ($user->email_verified_at) {
            return back()->with('info', 'This email is already verified.');
        }

        // Delete old tokens
        EmailVerificationToken::where('user_id', $user->id)->delete();

        // Generate new verification token
        $token = Str::random(64);
        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send verification email
        Mail::send(new VerifyEmailMail($user, $token));

        return back()->with('success', 'A new verification link has been sent to your email.');
    }

    /**
     * Update user role via form submission
     */
    public function updateRole(Request $request, User $user)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin' || auth()->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,customer',
        ]);

        $user->update(['role' => $validated['role']]);
        return redirect()->route('users.index')->with('success', 'User role updated successfully');
    }

    /**
     * Update user status via form submission
     */
    public function updateStatus(Request $request, User $user)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin' || auth()->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user->update(['status' => $validated['status']]);
        return redirect()->route('users.index')->with('success', 'User status updated successfully');
    }
}
