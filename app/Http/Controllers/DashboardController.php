<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PersonalInfo;
use App\Services\WorkScheduleService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private WorkScheduleService $workScheduleService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectCount = Project::count();
        $personalCount = PersonalInfo::count();

        $scheduleData = $this->workScheduleService->getWeeklyScheduleData();

        return view('dashboard', [
            'projectCount'      => $projectCount,
            'personalCount'     => $personalCount,
            'leaderSchedule'    => $scheduleData['leaderSchedule'],
            'scheduleWeekRange' => [$scheduleData['weekStart'], $scheduleData['weekEnd']],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
