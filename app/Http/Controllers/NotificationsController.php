<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResources;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id', 'name'];

        $query = Notification::with(['user']);
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);

        $notifes = $query->paginate(20);
        return NotificationResources::collection($notifes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exclude admin users
        $users = User::whereNotIn('role', ['admin'])->get();
        return view("admin.notification.add", ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|max:64',
            'text' => 'required'
        ]);

        // Find the user using the provided user_id
        $user = User::findOrFail($validated['user_id']);
        
        // Create the notification
        $notification = Notification::create([
            'user_id' => $user->id,
            'subject' => $validated['subject'],
            'text' => $validated['text']
        ]);

        return response()->json([
            'message' => 'Notification created successfully',
            'data' => $notification,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        $users = User::whereNotIn('role', ['admin'])->get();
        return view("admin.notification.edit", ['notification' => $notification]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:notifications,id',
            'subject' => 'required|max:64',
            'text' => 'required'
        ]);

        $notification = Notification::findOrFail($validated['id']);

        $notification->update([
            'subject' => $validated['subject'],
            'text' => $validated['text']
        ]);

        return response()->json([
            'message' => 'Notification updated successfully',
            'data' => $notification,
        ]);
    }

    public function read(Request $request){
        $validated = $request->validate([
            'id' => 'required|integer|exists:notifications,id'
        ]);
        $notification = Notification::findorFail($validated['id']);
        $notification->update(["isRead" => 1]);
        return response()->json(['message' => "اعلان خوانده شد."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }
}
