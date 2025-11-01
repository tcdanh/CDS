<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = Project::with('principalInvestigator')
            ->latest()
            ->paginate(10);

        return view('project_management.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $personalInfos = PersonalInfo::orderBy('full_name')->get();

        return view('project_management.create', compact('personalInfos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        Project::create($data);

        return redirect()
            ->route('project-management.index')
            ->with('success', 'Dự án đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $personalInfos = PersonalInfo::orderBy('full_name')->get();

        return view('project_management.edit', compact('project', 'personalInfos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validateData($request, $project);

        $project->update($data);

        return redirect()
            ->route('project-management.index')
            ->with('success', 'Dự án đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('project-management.index')
            ->with('success', 'Dự án đã được xoá.');
    }

    private function validateData(Request $request, ?Project $project = null): array
    {
        $request->merge([
            'total_budget' => $request->filled('total_budget')
                ? str_replace(',', '', $request->input('total_budget'))
                : null,
        ]);

        return $request->validate([
            'name_vi' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'industry_group' => ['nullable', 'string', 'max:255'],
            'research_type' => ['nullable', 'string', 'max:255'],
            'implementation_time' => ['nullable', 'string', 'max:255'],
            'principal_investigator_id' => ['nullable', 'exists:personal_infos,id'],
            'science_secretary' => ['nullable', 'string', 'max:255'],
            'total_budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
