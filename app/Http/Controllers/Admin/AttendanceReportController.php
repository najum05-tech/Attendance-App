<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;

class AttendanceReportController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.reports.index', compact('students'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        if ($request->user_id) {
            // Per Student Report
            $user = \App\Models\User::findOrFail($request->user_id);
            $attendances = Attendance::with('user')
                ->where('user_id', $request->user_id)
                ->whereBetween('created_at', [$request->from_date, $request->to_date])
                ->get();
            
            $present = $attendances->where('status', 'present')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $leave = $attendances->where('status', 'leave')->count();
            
            $leaveRequests = \App\Models\LeaveRequest::where('user_id', $request->user_id)
                ->whereBetween('from_date', [$request->from_date, $request->to_date])
                ->get();
            $approvedLeaves = $leaveRequests->where('status', 'approved')->count();
            
            return view('admin.reports.student', compact('user', 'attendances', 'present', 'absent', 'leave', 'approvedLeaves', 'request'));
        } else {
            // System-wide Report
            $users = \App\Models\User::where('role', 'student')->get();
            $report = [];
            
            foreach ($users as $user) {
                $userAttendances = Attendance::where('user_id', $user->id)
                    ->whereBetween('created_at', [$request->from_date, $request->to_date])
                    ->get();
                
                $present = $userAttendances->where('status', 'present')->count();
                $absent = $userAttendances->where('status', 'absent')->count();
                $leave = $userAttendances->where('status', 'leave')->count();
                
                // Calculate grade using configurable thresholds
                $totalPresent = $present;
                $gradeA = \Illuminate\Support\Facades\Cache::get('grade_a', 26);
                $gradeB = \Illuminate\Support\Facades\Cache::get('grade_b', 20);
                $gradeC = \Illuminate\Support\Facades\Cache::get('grade_c', 15);
                $gradeD = \Illuminate\Support\Facades\Cache::get('grade_d', 10);
                
                if ($totalPresent >= $gradeA) $grade = 'A';
                elseif ($totalPresent >= $gradeB) $grade = 'B';
                elseif ($totalPresent >= $gradeC) $grade = 'C';
                elseif ($totalPresent >= $gradeD) $grade = 'D';
                else $grade = 'F';
                
                $report[] = [
                    'username' => $user->username,
                    'present' => $present,
                    'absent' => $absent,
                    'leave' => $leave,
                    'grade' => $grade,
                ];
            }
            
            return view('admin.reports.system', compact('report', 'request'));
        }
    }
}
