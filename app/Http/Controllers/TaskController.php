<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function __construc()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters(['title', 'is_done'])
            ->defaultSort('created_at')
            ->allowedSorts(['title', 'is_done', 'created_at'])
            ->get();

        return new TaskCollection($tasks);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

}
