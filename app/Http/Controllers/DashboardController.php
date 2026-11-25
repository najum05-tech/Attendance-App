<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\Role;
use App\Models\Grade;

class DashboardController extends Controller
{
   public function index()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        return $this->adminDashboard(); // call method instead of redirect
    }

    if ($user->role === 'student') {
        return redirect()->route('user.dashboard');
    }

    return abort(403, 'Unknown user role');
}
public function redirectBasedOnRole()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
}


    public function adminDashboard()
    {
        $totalUsers = User::count();
        $presentToday = Attendance::where('date', date('Y-m-d')) 
                              ->where('status', 'present') 
                              ->count();
        $pendingLeaves = LeaveRequest::where('status', 'Pending')->count();
        $tasks = Task::count();
        $roles = Role::count();
        // $grades = Grade::count();
        $grades = User::where('role', 'student')->count();

        return view('admin.admin', compact(
            'totalUsers',
            'presentToday',
            'pendingLeaves',
            'tasks',
            'roles',
            'grades'
        ));
    }
}
