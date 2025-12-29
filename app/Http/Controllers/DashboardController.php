<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PersonalInfo;
//use App\Services\WorkScheduleService;
use App\Models\WeeklyWorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /*public function __construct(private WorkScheduleService $workScheduleService)
    {
    } */
    private const CAP_NHAT = [1, 5, 12];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectCount = Project::count();
        $personalCount = PersonalInfo::count();
        //$scheduleData = $this->workScheduleService->getWeeklyScheduleData();
        $currentUser = auth()->user();
        $canUpdateWeeklySchedule = $currentUser
            ? in_array($currentUser->role_id, self::CAP_NHAT, true)
            : false;

        $now = Carbon::now();
        $currentWeekLabel = sprintf(
            '%s - %s',
            $now->copy()->startOfWeek()->format('d/m/Y'),
            $now->copy()->endOfWeek()->format('d/m/Y')
        );

        $weeklySchedules = WeeklyWorkSchedule::query()
            ->orderByDesc('updated_at')
            ->limit(4)
            ->get();

        return view('dashboard', [
            'projectCount'      => $projectCount,
            'personalCount'     => $personalCount,
            'weeklySchedules'   => $weeklySchedules,
            'currentWeekLabel'  => $currentWeekLabel,
            'canUpdateWeeklySchedule' => $canUpdateWeeklySchedule,
            'driveUrl' => config('work_schedules.drive_url'),
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
