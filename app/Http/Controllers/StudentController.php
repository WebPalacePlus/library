<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\queues;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function profile()
    {
        $student = auth()->user();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:63',
            'password' => 'required|password|max:63',
        ]);

        $student = auth()->user();
        $student->update($request->only(['name', 'password']));
        return back()->with('success', 'Profile updated successfully!');
    }


}
