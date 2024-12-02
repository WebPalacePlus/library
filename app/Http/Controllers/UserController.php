<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResources;
use App\Http\Resources\UserResources;
use App\Models\Field;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id', 'name'];

        $query = User::query();
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);

        $users = $query->paginate(20);
        return UserResources::collection($users);
    }

    public function notifications(Request $request){
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id', 'name'];

        $user_id = Auth::id();
        $query = Notification::where(['user_id' => $user_id, 'isRead' => 0]);
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);

        $users = $query->paginate(20);
        return NotificationResources::collection($users);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.user.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:64',
            'code' => 'required|string|max:10|min:10',
            'password' => 'required|string|max:64|min:8',
            'role' => 'required|in:student,admin'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'user created successfully',
            'data' => $user,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return new UserResources($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view("admin.user.edit", ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:64',
            'code' => 'required|string|max:10|min:10',
            'role' => 'required|in:student,admin'
        ]);
        
        $user = User::findOrFail($validatedData['id']);

        $user->update([
            'name' => $validatedData['name'],
            'code' => $validatedData['code'],
            'role' => $validatedData['role'],
        ]);
        return response()->json([
            'message' => 'user updated successfully',
            'data' => $user,
        ]);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'user deleted successfully',
        ]);
    }
}
