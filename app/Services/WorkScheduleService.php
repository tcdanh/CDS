<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WorkScheduleService
{
    /**
     * Lấy dữ liệu lịch làm việc theo tuần của lãnh đạo.
     */
    public function getWeeklyScheduleData(?Carbon $referenceDate = null): array
    {
        $reference = $referenceDate ? $referenceDate->copy() : Carbon::now();
        $weekStart = $reference->startOfWeek();
        $weekEnd = $reference->copy()->endOfWeek();

        $displayRoleIds = [1, 2, 4, 12];

        $schedules = WorkSchedule::with('user')
            ->whereBetween('scheduled_date', [
                $weekStart->toDateString(),
                $weekEnd->toDateString(),
            ])
            ->whereHas('user', function ($query) use ($displayRoleIds) {
                $query->whereIn('role_id', $displayRoleIds);
            })
            ->get();

        $schedulesByDate = $schedules
            ->groupBy(function (WorkSchedule $schedule) {
                return $schedule->scheduled_date->toDateString();
            })
            ->map(function (Collection $dayGroup) {
                $roleOrder = [
                    2 => 0,
                    1 => 1,
                    4 => 2,
                    12 => 3,
                ];

                return $dayGroup
                    ->sortBy(function (WorkSchedule $schedule) use ($roleOrder) {
                        $roleId = optional($schedule->user)->role_id;
                        $roleRank = $roleOrder[$roleId] ?? 999;

                        return sprintf('%s-%03d', $schedule->time_of_day, $roleRank);
                    })
                    ->values();
            });

        $leaderSchedule = $this->buildWeeklySchedule($weekStart->copy(), $schedulesByDate);

        return [
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'leaderSchedule' => $leaderSchedule,
            'schedules' => $schedules,
        ];
    }

    public function getAuthorizedUsers(array $roleIds): Collection
    {
        return User::query()
            ->whereIn('role_id', $roleIds)
            ->orderBy('name')
            ->get();
    }

    protected function buildWeeklySchedule(Carbon $weekStart, Collection $schedulesByDate): Collection
    {
        return collect(range(0, 4))->map(function (int $offset) use ($weekStart, $schedulesByDate) {
            $date = $weekStart->copy()->addDays($offset);
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
