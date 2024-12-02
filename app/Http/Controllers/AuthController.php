<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:10|min:10',
            'password' => 'required|string|max:63|min:8',
        ]);

        $user = User::where('code', $validated['username'])->first();
        $errors = [];

        if ($user) {
            if (Hash::check($validated['password'], $user->password)) {
                Auth::login($user);
                
                if ($user->role === 'student') {
                    return redirect('/user/dashboard');
                } elseif ($user->role === 'admin') {
                    return redirect('/admin/dashboard');
                }
            } else {
                $errors['password'] = 'رمز عبور اشتباه است.';
            }
        } else {
            $errors['login'] = 'کاربر وجود ندارد.';
        }

        return view("login")->withErrors($errors);
    }
}
