<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Educator\Requests\UploadFileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Domain\Project\Models\TaskFile;
use Domain\Project\Models\Task;
use Domain\Project\Models\SubTask;

class FileController extends Controller
{
    protected function storeFile($file, $collection, $uploaderType, $uploaderId, $parentType = null, $parentId = null)
    {
        $path = $file->store("tasks/{$parentId}", 'public');

        $taskFile = TaskFile::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => $file->getClientOriginalName(),
            'file_name' => basename($path),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'collection_name' => $collection,
            'type' => 'file',
            'storage' => 'public',
            'url' => Storage::disk('public')->url($path),
            'path' => $path,
            'size' => $file->getSize(),
            'uploader_type' => $uploaderType,
            'uploader_id' => $uploaderId,
        ]);

        return $taskFile;
    }

    public function uploadTaskAttachment(UploadFileRequest $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $educator = $request->user();

        $file = $request->file('file');
        $taskFile = $this->storeFile($file, 'task_attachments', 'educator', $educator->id, 'task', $task->id);

        $task->files()->save($taskFile);

        return response()->json(['status' => true, 'message' => 'Uploaded', 'data' => $taskFile]);
    }

    public function uploadSubTaskAttachment(UploadFileRequest $request, $taskId, $subTaskId)
    {
        $sub = SubTask::where('id', $subTaskId)->where('task_id', $taskId)->firstOrFail();
        $educator = $request->user();
        $file = $request->file('file');
        $taskFile = $this->storeFile($file, 'subtask_attachments', 'educator', $educator->id, 'subtask', $sub->id);
        $sub->files()->save($taskFile);
        return response()->json(['status' => true, 'message' => 'Uploaded', 'data' => $taskFile]);
    }

    public function uploadDeliverable(UploadFileRequest $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $educator = $request->user();
        $file = $request->file('file');
        $taskFile = $this->storeFile($file, 'deliverables', 'educator', $educator->id, 'task', $task->id);
        $task->files()->save($taskFile);
        return response()->json(['status' => true, 'message' => 'Deliverable uploaded', 'data' => $taskFile]);
    }
}
