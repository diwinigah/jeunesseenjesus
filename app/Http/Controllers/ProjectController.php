<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function index(ProjectService $projectService): View
    {
        $projects = Project::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('projects.index', [
            'projects' => $projects,
            'projectService' => $projectService,
        ]);
    }

    public function show(Project $project, ProjectService $projectService): View
    {
        abort_unless($project->status->value === 'published', 404);

        return view('projects.show', [
            'project' => $project,
            'projectService' => $projectService,
        ]);
    }
}
