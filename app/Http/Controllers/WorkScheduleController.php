<?php

namespace App\Http\Controllers;

use App\Models\WorkSchedule;
use App\Services\WorkScheduleService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkScheduleController extends Controller
{
    private const MANAGER_ROLE_IDS = [1, 2, 4, 12];

    public function __construct(private WorkScheduleService $workScheduleService)
    {
    }

    public function index()
    {
        $scheduleData = $this->workScheduleService->getWeeklyScheduleData();
        $weekSchedules = $scheduleData['schedules']->sortBy(function (WorkSchedule $schedule) {
            return sprintf('%s-%s', $schedule->scheduled_date->format('Y-m-d'), $schedule->time_of_day);
        });

        $weekStart = $scheduleData['weekStart'];
        $weekEnd = $scheduleData['weekEnd'];

        $nextWeekStart = $weekStart->copy()->addWeek();
        $nextWeekEnd = $weekEnd->copy()->addWeek();

        $authorizedUsers = $this->workScheduleService->getAuthorizedUsers(self::MANAGER_ROLE_IDS);

        return view('administration.work_schedules.index', [
            'leaderSchedule' => $scheduleData['leaderSchedule'],
            'scheduleWeekRange' => [$weekStart, $weekEnd],
            'currentWeekSchedules' => $weekSchedules,
            'authorizedUsers' => $authorizedUsers,
            'canManageSchedule' => $this->canManageSchedule(),
            'nextWeekRange' => [$nextWeekStart, $nextWeekEnd],
        ]);
    }

    public function updateWeek(Request $request): RedirectResponse
    {
        $this->ensureCanManage();

        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();

        $validated = $request->validate([
            'schedule_id' => ['required', Rule::exists('work_schedules', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                $query->whereIn('role_id', self::MANAGER_ROLE_IDS);
            })],
            'scheduled_date' => ['required', 'date', function ($attribute, $value, $fail) use ($weekStart, $weekEnd) {
                $date = Carbon::parse($value);
                if ($date->lt($weekStart) || $date->gt($weekEnd)) {
                    $fail('Ngày phải nằm trong tuần hiện tại.');
                }
            }],
            'time_of_day' => ['required', Rule::in([WorkSchedule::TIME_OF_DAY_MORNING, WorkSchedule::TIME_OF_DAY_AFTERNOON])],
            'content' => ['required', 'string', 'max:255'],
            'time_range' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        /** @var WorkSchedule $schedule */
        $schedule = WorkSchedule::findOrFail($validated['schedule_id']);

        if ($schedule->scheduled_date->lt($weekStart) || $schedule->scheduled_date->gt($weekEnd)) {
            return back()
                ->withErrors(['schedule_id' => 'Lịch được chọn không thuộc tuần hiện tại.'])
                ->withInput();
        }

        $schedule->update([
            'user_id' => $validated['user_id'],
            'scheduled_date' => $validated['scheduled_date'],
            'time_of_day' => $validated['time_of_day'],
            'content' => $validated['content'],
            'time_range' => $validated['time_range'],
            'location' => $validated['location'],
            'notes' => $validated['notes'],
        ]);

        return back()->with('status', 'Cập nhật lịch tuần thành công.');
    }

    public function createNextWeek(Request $request): RedirectResponse
    {
        $this->ensureCanManage();

        $reference = Carbon::now()->addWeek();
        $nextWeekStart = $reference->copy()->startOfWeek();
        $nextWeekEnd = $reference->copy()->endOfWeek();

        $validated = $request->validate([
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.user_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                $query->whereIn('role_id', self::MANAGER_ROLE_IDS);
            })],
            'entries.*.scheduled_date' => ['required', 'date', function ($attribute, $value, $fail) use ($nextWeekStart, $nextWeekEnd) {
                $date = Carbon::parse($value);
                if ($date->lt($nextWeekStart) || $date->gt($nextWeekEnd)) {
                    $fail('Ngày phải nằm trong tuần kế tiếp.');
                }
            }],
            'entries.*.time_of_day' => ['required', Rule::in([WorkSchedule::TIME_OF_DAY_MORNING, WorkSchedule::TIME_OF_DAY_AFTERNOON])],
            'entries.*.content' => ['required', 'string', 'max:255'],
            'entries.*.time_range' => ['nullable', 'string', 'max:255'],
            'entries.*.location' => ['nullable', 'string', 'max:255'],
            'entries.*.notes' => ['nullable', 'string'],
        ], [
            'entries.required' => 'Vui lòng thêm ít nhất một hoạt động.',
            'entries.*.user_id.required' => 'Vui lòng chọn người phụ trách.',
            'entries.*.scheduled_date.required' => 'Vui lòng chọn ngày.',
            'entries.*.time_of_day.required' => 'Vui lòng chọn buổi.',
            'entries.*.content.required' => 'Nội dung công tác không được để trống.',
        ]);

        $entries = collect($validated['entries'])
            ->map(function (array $entry) {
                $entry['content'] = trim($entry['content']);

                foreach (['time_range', 'location', 'notes'] as $optionalField) {
                    if (! array_key_exists($optionalField, $entry)) {
                        $entry[$optionalField] = null;
                        continue;
                    }

                    if ($entry[$optionalField] === null) {
                        continue;
                    }

                    $entry[$optionalField] = trim((string) $entry[$optionalField]);

                    if ($entry[$optionalField] === '') {
                        $entry[$optionalField] = null;
                    }
                }

                return array_merge($entry, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })
            ->values();

        WorkSchedule::insert($entries->all());

        return back()->with('status', 'Đã tạo lịch mới cho tuần tới.');
    }

    private function ensureCanManage(): void
    {
        if (! $this->canManageSchedule()) {
            abort(403);
        }
    }

    private function canManageSchedule(): bool
    {
        $user = auth()->user();

        return $user && in_array($user->role_id, self::MANAGER_ROLE_IDS, true);
    }
}
