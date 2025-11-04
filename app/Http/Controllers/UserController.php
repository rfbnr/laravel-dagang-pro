<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserCredentialsMail;
use App\Mail\ForcePasswordChangeMail;

class UserController extends Controller

{
    public function index()
    {
        $users = User::paginate(7);
        return view('theem.pages.user.index', compact('users'));
    }

    public function create()
    {
        return view('theem.pages.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,cashier',
        ]);

        $randomPassword = Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($randomPassword),
            'force_password_change' => $request->has('force_password_change'),
        ]);

        Mail::to($user->email)->send(new SendUserCredentialsMail($user, $randomPassword, 'new'));

        return redirect()->route('user.index')->with('success', 'User created and credentials sent via email.');
    }

    public function show(User $user)
    {
        return view('theem.pages.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('theem.pages.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,cashier',

        ]);

        $oldEmail = $user->email;
        $newEmail = $request->email;

        $user->name = $request->name;
        $user->email = $newEmail;
        $user->role = $request->role;

        if ($oldEmail !== $newEmail) {
            $newPassword = Str::random(10);
            $user->password = bcrypt($newPassword);
            $user->force_password_change = true;
            $user->save();

            Mail::to($oldEmail)->send(new SendUserCredentialsMail($user, $newPassword, 'old'));
            Mail::to($newEmail)->send(new SendUserCredentialsMail($user, $newPassword, 'new'));
        } else {
            $user->save();
        }

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    // public function forcePasswordChange(User $user)
    // {
    //     // 1. Generate temporary password
    //     $tempPassword = Str::random(10);

    //     // 2. Update user
    //     $user->update([
    //         'password' => Hash::make($tempPassword),
    //         'force_password_change' => true,
    //     ]);

    //     // 3. Send email to user
    //     Mail::to($user->email)->send(new ForcePasswordChangeMail($user, $tempPassword));

    //     return back()->with('success', 'Temporary password sent and user must change it at next login.');
    // }

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $userId = $request->user_id;

        $exists = User::where('email', $email)
            ->where('id', '!=', $userId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
