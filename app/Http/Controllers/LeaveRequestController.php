<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Notifications\LeaveRequestSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    private const TCHC_ROLE = 'TCHC';
    private const LEADER_ROLES = ['Admin', 'Director', 'ViceDirector'];

    public function index(Request $request)
    {
        $user = $request->user();

        $query = LeaveRequest::query()->with('user')->latest();

        if (! $this->canReviewAll($user)) {
            $query->where('user_id', $user?->id);
        }

        $leaveRequests = $query->paginate(15)->withQueryString();

        $availableStatuses = [];

        foreach ($leaveRequests as $leaveRequest) {
            $availableStatuses[$leaveRequest->id] = $this->allowedStatusesForUser($user, $leaveRequest);
        }

        return view('administration.leave_requests.index', [
            'leaveRequests' => $leaveRequests,
            'statusLabels' => LeaveRequest::statusLabels(),
            'statusStyles' => LeaveRequest::statusStyles(),
            'canMarkTchc' => $this->canMarkTchc($user),
            'canDecide' => $this->canDecide($user),
            'availableStatuses' => $availableStatuses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days_requested' => ['required', 'numeric', 'min:0.5', 'max:365'],
            'reason' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'days_requested.min' => 'Số ngày nghỉ phải lớn hơn 0.',
        ]);

        $leaveRequest = LeaveRequest::create([
            'user_id' => $user?->id,
            'full_name' => $validated['full_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $validated['days_requested'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'status' => LeaveRequest::STATUS_PENDING,
        ]);

        $this->notifyTchcUsers($leaveRequest);

        return redirect()
            ->route('leave-requests.index')
            ->with('status', 'Đã gửi đơn xin nghỉ phép. TCHC sẽ kiểm tra trong thời gian sớm nhất.');
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $user = $request->user();

        $allowedStatuses = $this->allowedStatusesForUser($user, $leaveRequest);

        if (empty($allowedStatuses)) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $leaveRequest->status = $validated['status'];

        if (array_key_exists('notes', $validated)) {
            $leaveRequest->notes = $validated['notes'];
        }

        $leaveRequest->save();

        return back()->with('status', 'Cập nhật tình trạng đơn thành công.');
    }

    private function canReviewAll(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->canMarkTchc($user) || $this->canDecide($user);
    }

    private function canMarkTchc(?User $user): bool
    {
        return (bool) ($user?->hasRole(self::TCHC_ROLE));
    }

    private function canDecide(?User $user): bool
    {
        return (bool) ($user?->hasRole(self::LEADER_ROLES));
    }

    /**
     * @return array<int, string>
     */
    private function allowedStatusesForUser(?User $user, LeaveRequest $leaveRequest): array
    {
        if (! $user) {
            return [];
        }

        $statuses = [];

        if ($this->canMarkTchc($user) && in_array($leaveRequest->status, [LeaveRequest::STATUS_PENDING, LeaveRequest::STATUS_TCHC_REVIEWED], true)) {
            $statuses[] = LeaveRequest::STATUS_PENDING;
            $statuses[] = LeaveRequest::STATUS_TCHC_REVIEWED;
        }

        if ($this->canDecide($user) && $leaveRequest->status === LeaveRequest::STATUS_TCHC_REVIEWED) {
            $statuses[] = LeaveRequest::STATUS_APPROVED;
            $statuses[] = LeaveRequest::STATUS_REJECTED;
        }

        return array_values(array_unique($statuses));
    }

    private function notifyTchcUsers(LeaveRequest $leaveRequest): void
    {
        $tchcUsers = User::query()
            ->whereHas('role', function ($query) {
                $query->where('name', self::TCHC_ROLE);
            })
            ->get();

        if ($tchcUsers->isEmpty()) {
            return;
        }

        Notification::send($tchcUsers, new LeaveRequestSubmitted($leaveRequest));
    }
}
