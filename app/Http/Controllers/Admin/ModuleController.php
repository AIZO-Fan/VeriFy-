<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controllers;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controllers
{
    public function index()
    {
        $modules = Module::orderBy('order')->orderBy('id')->get();
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        return view('admin.modules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'color' => 'required|in:blue,purple,green,orange,cyan,pink,red,emerald',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'lessons' => 'required|array|min:1',
            'lessons.*' => 'required|string|max:2000',
        ]);

        $maxId = Module::max('id') ?? 0;
        $order = $validated['order'] ?? ($maxId + 1);

        // Filter out empty lessons
        $cleanLessons = array_values(array_filter($validated['lessons'], fn($l) => !empty(trim($l))));

        Module::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'order' => $order,
            'is_active' => $request->has('is_active'),
            'lessons' => $cleanLessons,
        ]);

        return redirect()->route('admin.modules.index')->with('success', 'Modul pembelajaran berhasil ditambahkan!');
    }

    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'color' => 'required|in:blue,purple,green,orange,cyan,pink,red,emerald',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'lessons' => 'required|array|min:1',
            'lessons.*' => 'required|string|max:2000',
        ]);

        $cleanLessons = array_values(array_filter($validated['lessons'], fn($l) => !empty(trim($l))));

        $module->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'order' => $validated['order'] ?? $module->order,
            'is_active' => $request->has('is_active'),
            'lessons' => $cleanLessons,
        ]);

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil diperbarui!');
    }

    public function toggle(Module $module)
    {
        $module->update(['is_active' => !$module->is_active]);
        $status = $module->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Modul {$module->title} berhasil {$status}!");
    }

    public function destroy(Module $module)
    {
        $title = $module->title;
        $module->delete();
        return redirect()->route('admin.modules.index')->with('success', "Modul '{$title}' berhasil dihapus!");
    }
}
