<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class TaskController extends Controller
{
    // List all tasks
    public function index() {
        $tasks = Task::with('user')->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    // Show form to create
    public function create() {
        $users = User::where('role', 'student')->get();
        return view('admin.tasks.create', compact('users'));
    }

    // Store new task
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_by' => auth()->id(),
            'assigned_to' => $request->user_id,
            'user_id' => $request->user_id, // For backward compatibility
            'status' => 'pending',
        ]);

        // Send WhatsApp notification
        try {
            $user = User::find($request->user_id);
            if ($user) {
                $whatsappService = new \App\Services\WhatsAppNotificationService();
                $whatsappService->taskAssigned($user, $task);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    // Show form to edit
    public function edit(Task $task) {
        $users = User::where('role', 'student')->get();
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    // Update task
    public function update(Request $request, Task $task) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->user_id,
            'user_id' => $request->user_id, // For backward compatibility
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Approve task submission
    public function approve(Request $request, Task $task) {
        $request->validate([
            'admin_feedback' => 'nullable|string|max:500',
        ]);

        $task->status = 'approved';
        // if ($request->admin_feedback) {
        //     $task->admin_feedback = $request->admin_feedback;
        // }
        $task->save();

        // Update task submission if exists
        $submission = \App\Models\TaskSubmission::where('task_id', $task->id)->where('user_id', $task->assigned_to ?? $task->user_id)->first();
        if ($submission) {
            $submission->status = 'approved';
            if ($request->admin_feedback) {
                $submission->admin_comment = $request->admin_feedback;
            }
            $submission->save();
        }

        // Send WhatsApp notification
        try {
            $user = User::find($task->assigned_to ?? $task->user_id);
            if ($user) {
                $whatsappService = new \App\Services\WhatsAppNotificationService();
                $whatsappService->taskApproved($user, $task);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Task approved successfully.');
    }

    // Reject task submission
    public function reject(Request $request, Task $task) {
        $request->validate([
            'admin_feedback' => 'required|string|max:500',
        ]);

        $task->status = 'rejected';
        // $task->admin_feedback = $request->admin_feedback;
        $task->save();

        // Update task submission if exists
        $submission = \App\Models\TaskSubmission::where('task_id', $task->id)->where('user_id', $task->assigned_to ?? $task->user_id)->first();
        if ($submission) {
            $submission->status = 'rejected';
            $submission->admin_comment = $request->admin_feedback;
            $submission->save();
        }

        // Send WhatsApp notification
        try {
            $user = User::find($task->assigned_to ?? $task->user_id);
            if ($user) {
                $whatsappService = new \App\Services\WhatsAppNotificationService();
                $whatsappService->taskRejected($user, $task);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Task rejected.');
    }

    // Delete task
    public function destroy(Task $task) {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }
}
