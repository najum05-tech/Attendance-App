<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    public function index() {
        $tasks = Task::where('assigned_to', Auth::id())
            ->orWhere('user_id', Auth::id())
            ->get();
        return view('user.tasks.index', compact('tasks'));
    }

    public function edit(Task $task) {
        $userId = $task->assigned_to ?? $task->user_id;
        if ($userId !== Auth::id()) abort(403);
        return view('user.tasks.submit', compact('task'));
    }

    public function update(Request $request, Task $task) {
        $userId = $task->assigned_to ?? $task->user_id;
        if ($userId !== Auth::id()) abort(403);

        $request->validate([
            'response' => 'required|string',
            'attachment' => 'nullable|file|max:2048'
        ]);

        // 1. Update the main Task status (ONLY status, not submission/attachment)
        $task->status = 'completed';
        $task->save();

        // 2. Prepare data for the Submission
        $submissionData = [
            'submission' => $request->response,
            'status' => 'pending',
        ];

        // 3. Handle Attachment Logic
        // We only update the attachment field if a NEW file is uploaded.
        // This prevents overwriting an existing file with null if the user just edits the text.
        if ($request->hasFile('attachment')) {
            $submissionData['attachment'] = $request->file('attachment')->store('task-attachments', 'public');
        }

        // 4. Create or Update the TaskSubmission
        \App\Models\TaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ],
            $submissionData
        );

        return redirect()->route('user.tasks.index')->with('success', 'Task submitted successfully.');
    }
}