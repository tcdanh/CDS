@php
    $scheduleDays = ($leaderSchedule ?? null) instanceof \Illuminate\Support\Collection
        ? $leaderSchedule
        : collect($leaderSchedule ?? []);
    $weekRangeLabel = null;

    if (is_array($scheduleWeekRange ?? null) && count($scheduleWeekRange) === 2) {
        [$weekStart, $weekEnd] = $scheduleWeekRange;

        if ($weekStart instanceof \Carbon\Carbon && $weekEnd instanceof \Carbon\Carbon) {
            $weekRangeLabel = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m/Y');
        }
    }
@endphp

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 pb-0">
    <div>
      <h3 class="card-title mb-0">Lịch công tác lãnh đạo</h3>&nbsp;
      @if ($weekRangeLabel)
        <span class="text-muted small"> (Tuần {{ $weekRangeLabel }})</span>
      @endif
    </div>
    <span class="badge text-bg-primary rounded-pill">Thứ 2 - Thứ 6</span>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="text-nowrap" style="width: 120px;">Buổi</th>
            @foreach ($scheduleDays as $day)
              <th class="text-center">
                <div class="fw-semibold">{{ $day['label'] ?? '' }}</div>
                @if (!empty($day['date']) && $day['date'] instanceof \Carbon\Carbon)
                  <div class="text-muted small">{{ $day['date']->format('d/m') }}</div>
                @endif
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach (['morning' => 'Buổi sáng', 'afternoon' => 'Buổi chiều'] as $slotKey => $slotLabel)
            <tr>
              <th class="text-nowrap align-top">{{ $slotLabel }}</th>
              @foreach ($scheduleDays as $day)
                @php
                  $entriesRaw = $day[$slotKey] ?? [];
                  $entries = $entriesRaw instanceof \Illuminate\Support\Collection
                      ? $entriesRaw
                      : collect($entriesRaw);
                @endphp
                <td class="align-top">
                  @php
                      // Thứ tự mong muốn cho role_id
                      $roleOrder = [2, 1, 4, 12];

                      // Nhóm các lịch trong ô này theo role_id của user (giữ cả các lịch chưa có user để hiển thị riêng)
                      $groupedByRole = $entries
                          ->filter(fn ($e) => $e->relationLoaded('user') && $e->user)
                          ->groupBy(fn ($e) => $e->user->role_id);

                      $extraRoleIds = $groupedByRole->keys()->diff($roleOrder)->values();

                      // Lấy danh sách role_id theo đúng thứ tự ưu tiên và bổ sung các role khác ở cuối
                      $orderedRoleIds = collect($roleOrder)
                          ->merge($extraRoleIds)
                          ->filter(fn ($roleId) => isset($groupedByRole[$roleId]))
                          ->values();
                  @endphp

                  @if ($orderedRoleIds->isEmpty())
                      <span class="text-muted fst-italic">Không có công tác</span>
                  @else
                      @foreach ($orderedRoleIds as $idx => $roleId)
                          @foreach ($groupedByRole[$roleId] as $entry)
                              <div class="mb-3">
                                  <div class="fw-semibold">{{ $entry->content }}</div>

                                  @if ($entry->time_range)
                                      <div class="text-muted small">
                                          <i class="bi bi-clock me-1"></i>{{ $entry->time_range }}
                                      </div>
                                  @endif

                                  @if ($entry->location)
                                      <div class="small">
                                          <i class="bi bi-geo-alt me-1"></i>{{ $entry->location }}
                                      </div>
                                  @endif

                                  @if ($entry->notes)
                                      <div class="small text-muted fst-italic">
                                          {!! nl2br(e($entry->notes)) !!}
                                      </div>
                                  @endif

                                  @if ($entry->relationLoaded('user') && $entry->user)
                                      <div class="small text-body-secondary">
                                          @php
                                              $roleId = $entry->user->role_id;
                                              $badgeClass = match ($roleId) {
                                                  1 => 'text-bg-success',
                                                  2 => 'text-bg-warning',
                                                  4 => 'text-bg-primary',
                                                  12 => 'text-bg-info',
                                                  default => 'text-bg-secondary',
                                              };
                                          @endphp
                                          <span class="badge {{ $badgeClass }}">{{ $entry->user->name }}</span>
                                      </div>
                                  @endif
                              </div>
                          @endforeach

                          {{-- Nếu chưa phải nhóm role cuối cùng thì vẽ 1 gạch ngang để phân tách --}}
                          @if ($idx < $orderedRoleIds->count() - 1)
                              <hr class="my-2">
                          @endif
                      @endforeach
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>