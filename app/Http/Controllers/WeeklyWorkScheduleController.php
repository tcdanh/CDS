<?php

namespace App\Http\Controllers;

use App\Models\WeeklyWorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WeeklyWorkScheduleController extends Controller
{
    private const CAP_NHAT = [1, 5, 12];

    public function store(Request $request): RedirectResponse
    {
        $currentUser = auth()->user();

        if (! $currentUser || ! in_array($currentUser->role_id, self::CAP_NHAT, true)) {
            abort(403);
        }

        $validated = $request->validate([
            'file_url' => ['required', 'url', 'max:2048'],
        ], [
            'file_url.required' => 'Vui lòng nhập đường dẫn file lịch công tác.',
            'file_url.url' => 'Đường dẫn file không hợp lệ.',
        ]);

        $now = Carbon::now();
        $weekLabel = sprintf(
            '%s - %s',
            $now->copy()->startOfWeek()->format('d/m/Y'),
            $now->copy()->endOfWeek()->format('d/m/Y')
        );

        $schedule = WeeklyWorkSchedule::query()
            ->where('week_label', $weekLabel)
            ->first();

        if ($schedule) {
            $schedule->update([
                'file_url' => $validated['file_url'],
                'updated_by' => $currentUser->id,
            ]);
        } else {
            WeeklyWorkSchedule::create([
                'week_label' => $weekLabel,
                'file_url' => $validated['file_url'],
                'updated_by' => $currentUser->id,
            ]);
        }

        $excessCount = WeeklyWorkSchedule::count() - 4;

        if ($excessCount > 0) {
            WeeklyWorkSchedule::query()
                ->orderBy('updated_at')
                ->limit($excessCount)
                ->delete();
        }

        return back()->with('status', 'Đã cập nhật lịch công tác tuần mới.');
    }
}

