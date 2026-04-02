<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // ── List all users ─────────────────────────────────────────
    public function index()
    {
        $users     = User::orderBy('name')->get();
        $pageTitle = 'Manage Users';
        return view('admin.users.index', compact('users', 'pageTitle'));
    }

    // ── Show create form ───────────────────────────────────────
    public function create()
    {
        $pageTitle = 'Add User';
        return view('admin.users.form', compact('pageTitle'));
    }

    // ── Store new user ─────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150|unique:users,email',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil ditambahkan.');
    }

    // ── Show edit form ─────────────────────────────────────────
    public function edit(User $user)
    {
        $pageTitle = 'Edit User';
        return view('admin.users.form', compact('user', 'pageTitle'));
    }

    // ── Update user (name & email only) ───────────────────────
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil diperbarui.');
    }

    // ── Delete user ────────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus.');
    }

    // ── My Profile page ────────────────────────────────────────
    public function profile()
    {
        $user      = Auth::user();
        $pageTitle = 'My Profile';
        return view('admin.users.profile', compact('user', 'pageTitle'));
    }

    // ── Update own profile (name & email) ─────────────────────
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile')
                         ->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Update own password ────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => ['required', 'confirmed', Password::min(8)
                                        ->mixedCase()
                                        ->numbers()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                         ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.profile')
                         ->with('success', 'Password berhasil diperbarui.');
    }

    // ── Reset password for another user ───────────────────────
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Password user "' . $user->name . '" berhasil direset.');
    }
}
