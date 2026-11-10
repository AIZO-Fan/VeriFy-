<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::orderBy('sort_order', 'asc')->withCount('sections')->get();
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        $nextOrder = (Module::max('sort_order') ?? 0) + 1;
        return view('admin.modules.create', compact('nextOrder'));
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
            'lessons.*.title' => 'required|string|max:255',
            'lessons.*.content' => 'required|string|max:5000',
        ]);

        $maxOrder = Module::max('sort_order') ?? 0;
        $order = $validated['order'] ?? ($maxOrder + 1);
        $isActive = $request->has('is_active');

        // Format data lessons untuk disimpan ke kolom JSON (opsional cadangan)
        $jsonLessons = collect($validated['lessons'])->map(fn($l) => $l['title'] . ': ' . $l['content'])->toArray();

        $module = Module::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'sort_order' => $order,
            'status' => $isActive ? 'published' : 'draft',
            'is_active' => $isActive,
            'lessons' => $jsonLessons,
        ]);

        // Simpan setiap poin materi ke tabel module_sections beserta judul kustomnya
        foreach ($validated['lessons'] as $index => $lesson) {
            $module->sections()->create([
                'title' => $lesson['title'],
                'type' => 'text',
                'content' => '<p>' . e($lesson['content']) . '</p>',
                'sort_order' => $index + 1,
            ]);
        }

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
            'lessons.*.title' => 'required|string|max:255',
            'lessons.*.content' => 'required|string|max:5000',
        ]);

        $isActive = $request->has('is_active');
        $jsonLessons = collect($validated['lessons'])->map(fn($l) => $l['title'] . ': ' . $l['content'])->toArray();

        $module->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'sort_order' => $validated['order'] ?? $module->sort_order,
            'status' => $isActive ? 'published' : 'draft',
            'is_active' => $isActive,
            'lessons' => $jsonLessons,
        ]);

        // Perbarui data section di database sesuai inputan baru
        $module->sections()->delete();
        foreach ($validated['lessons'] as $index => $lesson) {
            $module->sections()->create([
                'title' => $lesson['title'],
                'type' => 'text',
                'content' => '<p>' . e($lesson['content']) . '</p>',
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil diperbarui!');
    }

    public function toggle(Module $module)
    {
        $newStatus = ($module->status === 'published') ? 'draft' : 'published';
        $newActive = ($newStatus === 'published');

        $module->update([
            'status' => $newStatus,
            'is_active' => $newActive
        ]);

        $statusText = $newActive ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Modul {$module->title} berhasil {$statusText}!");
    }

    public function destroy(Module $module)
    {
        $title = $module->title;
        $module->delete();
        return redirect()->route('admin.modules.index')->with('success', "Modul '{$title}' berhasil dihapus!");
    }
}