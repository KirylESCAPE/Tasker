<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service
    ) {
    }

    public function index(Request $request): View
    {
        $this->service->handleSession();

        [$tasks, $stats, $search, $status] = $this->service->prepareResult($request);

        return view('tasks.index', compact('tasks', 'search', 'status', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->service->handleSession();
        $this->service->createTask($this->service->validateFields($request));

        return redirect()->back()->with('success', 'Task created');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);
        $task->update($this->service->validateFields($request));

        return redirect()->back()->with('success', 'Task updated');
    }

    public function delete(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted');
    }

    public function toggle(Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);
        $task->toggleCompletion();

        return redirect()->back();
    }
}
