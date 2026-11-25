<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Log;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $leaves = LeaveRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function show($id)
    {
        // Fetch the leave request with the associated user
        $leave = LeaveRequest::with('user')->findOrFail($id);

        // Return the detailed view
        return view('admin.leaves.show', compact('leave'));
    }

    public function approve(Request $request, LeaveRequest $leave)
    {
        $leave->status = 'approved';
        if ($request->has('comment') && $request->comment) {
            $leave->admin_comment = $request->comment;
        }
        $leave->save();

        // Send approval notification
        try {
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $message = "✅ Leave Request Approved!\n\n";
            $message .= "Hello {$leave->user->name},\n";
            $message .= "Your leave request from " . \Carbon\Carbon::parse($leave->from_date)->format('M d') . " to " . \Carbon\Carbon::parse($leave->to_date)->format('M d') . " has been approved.\n";
            if ($leave->admin_comment) {
                $message .= "Comment: {$leave->admin_comment}\n";
            }
            $message .= "Thank you!";
            
            $whatsappService->send($leave->user->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp approval notification: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Leave approved and notification sent.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $leave->status = 'rejected';
        $leave->admin_comment = $request->comment;
        $leave->save();

        // Send rejection notification
        try {
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $message = "❌ Leave Request Rejected\n\n";
            $message .= "Hello {$leave->user->name},\n";
            $message .= "Your leave request from " . \Carbon\Carbon::parse($leave->from_date)->format('M d') . " to " . \Carbon\Carbon::parse($leave->to_date)->format('M d') . " has been rejected.\n";
            $message .= "Reason: {$leave->admin_comment}\n\n";
            $message .= "Please contact HR if you have any questions.";
            
            $whatsappService->send($leave->user->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp rejection notification: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Leave rejected and notification sent.');
    }
}
