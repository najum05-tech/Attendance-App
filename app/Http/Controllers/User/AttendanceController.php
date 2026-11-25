<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index() {
        $attendances = Attendance::where('user_id', Auth::id())->get();
        return view('user.attendance.view', compact('attendances'));
    }

    public function create() {
        $today = now()->format('Y-m-d');
        $attendanceMarked = Attendance::where('user_id', Auth::id())->whereDate('created_at', $today)->exists();
        return view('user.attendance.mark', compact('attendanceMarked'));
    }

    public function store(Request $request) {
        // Only allow marking once per day
        $today = now()->format('Y-m-d');
        $exists = Attendance::where('user_id', Auth::id())->whereDate('date', $today)->first();

        if ($exists) {
            return redirect()->back()->with('error', 'You have already marked attendance today.');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'status' => 'present',
            'date' => now()->toDateString(),
        ]);

        // Send WhatsApp notification
        try {
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $whatsappService->attendanceMarked(Auth::user());
        } catch (\Exception $e) {
            // Log error but don't break the flow
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        return redirect()->route('user.attendance.index')->with('success', 'Attendance marked successfully!');
    }

    public function show(Attendance $attendance) {
        // Users can only view their own attendance
        if ($attendance->user_id !== Auth::id()) {
            abort(403);
        }
        return view('user.attendance.show', compact('attendance'));
    }

    // Users cannot delete attendance - no destroy method
}
