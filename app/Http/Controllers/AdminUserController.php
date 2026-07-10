<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        // Base stats (unfiltered)
        $allUsers = User::whereIn('role', ['student', 'evaluator'])->get();

        // Filtered Query
        $query = User::whereIn('role', ['student', 'evaluator']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->get();

        return view('admin.users', compact('users', 'allUsers'));
    }

    public function storeEvaluator(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'evaluator',
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Evaluator account created successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Cannot delete an admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', ucfirst($user->role) . ' account deleted successfully.');
    }
}
