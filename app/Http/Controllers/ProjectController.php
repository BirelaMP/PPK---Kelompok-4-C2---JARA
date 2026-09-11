<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('owner_id', auth()->id())
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => auth()->id(),
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil dibuat!');
    }

    public function show(Project $project)
    {
        $this->checkOwnership($project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->checkOwnership($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->checkOwnership($project);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update(
            $request->only('name', 'description')
        );

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $this->checkOwnership($project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil dihapus!');
    }

    private function checkOwnership(Project $project): void
    {
        if ($project->owner_id !== auth()->id()) {
            abort(403);
        }
    }
}