<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const STATUSES = ['Pending', 'In Progress', 'Completed'];

    public function index(Request $request): View
    {
        $tasks = $request->user()->tasks()->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('status', 'เพิ่มงานเรียบร้อยแล้ว');
    }

    public function update(Request $request, int $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $request->user()->tasks()->findOrFail($task)->update($validated);

        return redirect()->route('tasks.index')->with('status', 'บันทึกการแก้ไขเรียบร้อยแล้ว');
    }

    public function destroy(Request $request, int $task): RedirectResponse
    {
        $request->user()->tasks()->findOrFail($task)->delete();

        return redirect()->route('tasks.index')->with('status', 'ลบงานเรียบร้อยแล้ว');
    }
}
