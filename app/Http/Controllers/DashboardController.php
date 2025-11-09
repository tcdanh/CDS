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

        // 2. Định nghĩa thứ tự ưu tiên role: 4 -> 1 -> 5
        $roleOrder = [
            2 => 0,
            1 => 1,
            4 => 2,
        ];

        // 3. LẤY DỮ LIỆU LỊCH TRƯỚC (đây là chỗ bạn đang thiếu)
        $schedules = WorkSchedule::with('user')
            ->whereBetween('scheduled_date', [
                $weekStart->toDateString(),
                $weekEnd->toDateString(),
            ])
            ->whereHas('user', function ($query) {
                // lọc user role 1,4,5
                $query->whereIn('role_id', [1, 2, 4]);
            })
            ->get();
        // 4. Group theo ngày, rồi trong mỗi ngày sắp theo ca + thứ tự role 4,1,5
        $schedulesByDate = $schedules
            ->groupBy(function (WorkSchedule $schedule) {
                // Nhóm theo ngày YYYY-MM-DD
                return $schedule->scheduled_date->toDateString();
            })
            ->map(function ($dayGroup) use ($roleOrder) {
                // $dayGroup: Collection các WorkSchedule trong cùng một ngày

                return $dayGroup
                    ->sortBy(function (WorkSchedule $schedule) use ($roleOrder) {
                        // Sắp trước theo ca, sau đó theo roleOrder
                        $roleId   = $schedule->user->role_id;
                        $roleRank = $roleOrder[$roleId] ?? 999; // role lạ thì đẩy xuống cuối

                        // time_of_day giả sử là chuỗi "morning", "afternoon", "evening"
                        // Kết hợp vào key để sort ổn định: ca + thứ tự role
                        return sprintf('%s-%03d', $schedule->time_of_day, $roleRank);
                    })
                    ->values(); // reset lại index cho Collection
            });

        //$leaderSchedule = $this->buildWeeklySchedule($weekStart, $schedulesByDate);

        //return view('dashboard', [
        //    'projectCount' => $projectCount, 'personalCount' => $personalCount, 'leaderSchedule' => $leaderSchedule,
        //    'scheduleWeekRange' => [$weekStart, $weekEnd],
        //]);
        // 5. Xây lịch hiển thị tuần (giữ nguyên helper của bạn)
        $leaderSchedule = $this->buildWeeklySchedule($weekStart, $schedulesByDate);

        return view('dashboard', [
            'projectCount'      => $projectCount,
            'personalCount'     => $personalCount,
            'leaderSchedule'    => $leaderSchedule,
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
