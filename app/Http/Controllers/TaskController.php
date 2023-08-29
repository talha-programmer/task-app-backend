<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:all,completed,incomplete',
        ]);

        $tasks = Task::query() 
            ->when($request->filter == 'completed', fn($q) => $q->where('completed', true))
            ->when($request->filter == 'incomplete', fn($q) => $q->where('completed', false))
            ->get();

        $tasks = TaskResource::collection($tasks);
        $response = ResponseUtil::getResponseArray(compact('tasks'), true);

        return $response;
    }

    public function save(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:tasks,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'completed' => 'nullable|boolean'
        ]);

        $task = Task::findOrNew($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->completed = $request->completed;
        $task->save();

        $task = new TaskResource($task);
        $response = ResponseUtil::getResponseArray(['task' => $task], true, 'Saved successfully');
        return $response;

    }


    public function completed(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tasks,id'
        ]);

        $task = Task::find($request->id);
        $task->completed = !$task->completed;
        $task->save();

        $task = new TaskResource($task);
        $response = ResponseUtil::getResponseArray(compact('task'), true, 'Updated successfully');

        return $response;
    }

}
