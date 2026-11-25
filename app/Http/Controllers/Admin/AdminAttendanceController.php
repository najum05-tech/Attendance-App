<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.attendance.index', compact('attendances'));
    }

    public function create()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.attendance.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Present,Absent,Leave',
            'date' => 'required|date',
        ]);

        Attendance::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'date' => $request->date,
        ]);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance added.');
    }

    public function edit(Attendance $attendance)
    {
        $students = User::where('role', 'student')->get();
        return view('admin.attendance.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Present,Absent,Leave',
            'date' => 'required|date',
        ]);

        $attendance->update([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'created_at' => $request->date,
        ]);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted.');
    }
}
