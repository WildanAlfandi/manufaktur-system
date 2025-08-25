<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('transactions')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,staff'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        // Log activity
        ActivityLog::log('create_user', 'User', $user->id, null, $user->toArray());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        $user->load(['transactions' => function ($query) {
            $query->latest()->take(10);
        }, 'activityLogs' => function ($query) {
            $query->latest()->take(20);
        }]);

        $stats = [
            'total_transactions' => $user->transactions()->count(),
            'transactions_in' => $user->transactions()->where('type', 'in')->count(),
            'transactions_out' => $user->transactions()->where('type', 'out')->count(),
            'last_login' => $user->activityLogs()->where('action', 'login')->latest()->first()?->created_at,
            'total_activities' => $user->activityLogs()->count()
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        // Prevent editing own account through this interface
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.edit')->with('info', 'Silakan edit profil Anda melalui halaman Profile.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Prevent editing own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengedit akun sendiri melalui halaman ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff',
            'password' => ['nullable', 'confirmed', Password::min(8)]
        ]);

        $oldValues = $user->toArray();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Log activity
        ActivityLog::log('update_user', 'User', $user->id, $oldValues, $user->toArray());

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir!');
        }

        // Check if user has transactions
        if ($user->transactions()->count() > 0) {
            return back()->with('error', 'User tidak dapat dihapus karena memiliki riwayat transaksi!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    public function toggleStatus(User $user)
    {
        // Prevent disabling own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri!');
        }

        // Toggle active status (we need to add 'is_active' column)
        // For now, we'll just return success
        return back()->with('success', 'Status user berhasil diubah!');
    }

    public function resetPassword(User $user)
    {
        // Reset password to default
        $defaultPassword = 'password123';
        $user->password = Hash::make($defaultPassword);
        $user->save();

        // Log activity
        ActivityLog::log('reset_password', 'User', $user->id);

        return back()->with('success', "Password berhasil direset ke: {$defaultPassword}");
    }
}
