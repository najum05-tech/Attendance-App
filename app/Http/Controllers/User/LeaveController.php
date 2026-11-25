<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function index() {
        $leaves = LeaveRequest::where('user_id', Auth::id())->get();
        return view('user.leaves.index', compact('leaves'));
    }

    public function create() {
        return view('user.leaves.create');
    }

    public function store(Request $request) {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        $leave = LeaveRequest::create([
            'user_id' => Auth::id(),
            'from_date' => $request->start_date,
            'to_date' => $request->end_date,
            //'start_date' => $request->start_date, // Keep for backward compatibility
            //'end_date' => $request->end_date, // Keep for backward compatibility
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Send WhatsApp notification
        try {
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $notificationSent = $whatsappService->leaveRequested(Auth::user(), $leave);
            
            if (!$notificationSent) {
                Log::warning('Failed to send WhatsApp notification for leave request', [
                    'user_id' => Auth::id(),
                    'leave_id' => $leave->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'leave_id' => $leave->id,
                'error' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('user.leaves.index')->with('success', 'Leave request sent!');
    }

    public function show(LeaveRequest $leave) {
        return view('user.leaves.show', compact('leave'));
    }
}
