<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GradeConfigController extends Controller
{
    public function index()
    {
        $config = [
            'grade_a' => Cache::get('grade_a', 26),
            'grade_b' => Cache::get('grade_b', 20),
            'grade_c' => Cache::get('grade_c', 15),
            'grade_d' => Cache::get('grade_d', 10),
        ];
        return view('admin.grades.config', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'grade_a' => 'required|integer|min:0',
            'grade_b' => 'required|integer|min:0|lt:grade_a',
            'grade_c' => 'required|integer|min:0|lt:grade_b',
            'grade_d' => 'required|integer|min:0|lt:grade_c',
        ]);

        Cache::forever('grade_a', $request->grade_a);
        Cache::forever('grade_b', $request->grade_b);
        Cache::forever('grade_c', $request->grade_c);
        Cache::forever('grade_d', $request->grade_d);

        return redirect()->back()->with('success', 'Grading criteria updated successfully.');
    }
}

