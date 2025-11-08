<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PersonalInfo;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectCount = Project::count();
        $personalCount = PersonalInfo::count();

        // 🔹 Xác định ngày đầu tuần (thứ 2) và cuối tuần (chủ nhật)
        $weekStart = Carbon::now()->startOfWeek(); 
        $weekEnd = Carbon::now()->endOfWeek();

        $schedulesByDate = WorkSchedule::with('user')
            ->whereBetween('scheduled_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->whereHas('user', function ($query) {
                $query->whereIn('role_id', [1, 2, 4]);
            })
            ->orderBy('scheduled_date')
            ->orderBy('time_of_day')
            ->get()
            ->groupBy(function (WorkSchedule $schedule) {
                return $schedule->scheduled_date->toDateString();
            });

        $leaderSchedule = $this->buildWeeklySchedule($weekStart, $schedulesByDate);

        return view('dashboard', [
            'projectCount' => $projectCount, 'personalCount' => $personalCount, 'leaderSchedule' => $leaderSchedule,
            'scheduleWeekRange' => [$weekStart, $weekEnd],
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

    protected function buildWeeklySchedule(Carbon $weekStart, Collection $schedulesByDate): Collection
    {
        return collect(range(0, 4))->map(function (int $offset) use ($weekStart, $schedulesByDate) {
            $date = (clone $weekStart)->addDays($offset);
            $dateKey = $date->toDateString();
            $daySchedules = $schedulesByDate->get($dateKey, collect());

            return [
                'date' => $date,
                'label' => $this->formatVietnameseWeekday($date),
                'morning' => $daySchedules->where('time_of_day', WorkSchedule::TIME_OF_DAY_MORNING)->values(),
                'afternoon' => $daySchedules->where('time_of_day', WorkSchedule::TIME_OF_DAY_AFTERNOON)->values(),
            ];
        });
    }

    protected function formatVietnameseWeekday(Carbon $date): string
    {
        return match ($date->dayOfWeek) {
            Carbon::MONDAY => 'Thứ 2',
            Carbon::TUESDAY => 'Thứ 3',
            Carbon::WEDNESDAY => 'Thứ 4',
            Carbon::THURSDAY => 'Thứ 5',
            Carbon::FRIDAY => 'Thứ 6',
            Carbon::SATURDAY => 'Thứ 7',
            Carbon::SUNDAY => 'Chủ nhật',
            default => $date->translatedFormat('l'),
        };
    }
}
