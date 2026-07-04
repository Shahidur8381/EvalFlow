<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['student', 'evaluator'])
                     ->orderBy('role')
                     ->orderBy('name')
                     ->get();

        return view('admin.users', compact('users'));
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
