<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    // View all users
    public function index()
    {
        $users = User::withCount(['attendances', 'leaveRequests'])->get();
        return view('admin.users.index', compact('users'));
    }

    // View user attendance summary
    public function show(User $user)
    {
        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalPresent = $attendances->where('status', 'present')->count();
        $totalAbsent = $attendances->where('status', 'absent')->count();
        $totalLeave = $attendances->where('status', 'leave')->count();
        $totalDays = $attendances->count();
        
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', $user->id)->get();
        $approvedLeaves = $leaveRequests->where('status', 'approved')->count();
        $pendingLeaves = $leaveRequests->where('status', 'pending')->count();
        $rejectedLeaves = $leaveRequests->where('status', 'rejected')->count();
        
        return view('admin.users.show', compact(
            'user', 
            'attendances', 
            'totalPresent', 
            'totalAbsent', 
            'totalLeave', 
            'totalDays',
            'approvedLeaves',
            'pendingLeaves',
            'rejectedLeaves',
            'leaveRequests'
        ));
    }

    // Edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
      if ($request->hasFile('avatar')) {
    if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
    }
    $avatarPath = $request->file('avatar')->store('avatars', 'public');
} else {
    $avatarPath = $user->avatar;
}

$user->update([
    'username' => $request->username,
    'email' => $request->email,
    'role' => $request->role,
    'avatar' => $avatarPath,
]);
 


        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
