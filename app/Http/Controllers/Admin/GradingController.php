<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;

class GradingController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->get();
        $grades = [];

        // Get configurable grade thresholds
        $gradeA = \Illuminate\Support\Facades\Cache::get('grade_a', 26);
        $gradeB = \Illuminate\Support\Facades\Cache::get('grade_b', 20);
        $gradeC = \Illuminate\Support\Facades\Cache::get('grade_c', 15);
        $gradeD = \Illuminate\Support\Facades\Cache::get('grade_d', 10);

        foreach ($students as $student) {
            $presentCount = Attendance::where('user_id', $student->id)->where('status', 'present')->count();

            if ($presentCount >= $gradeA) $grade = 'A';
            elseif ($presentCount >= $gradeB) $grade = 'B';
            elseif ($presentCount >= $gradeC) $grade = 'C';
            elseif ($presentCount >= $gradeD) $grade = 'D';
            else $grade = 'F';

            $grades[$student->id] = [
                'name' => $student->username, 
                'present' => $presentCount, 
                'grade' => $grade
            ];
        }

        return view('admin.grades.index', compact('grades', 'gradeA', 'gradeB', 'gradeC', 'gradeD'));
    }
}
