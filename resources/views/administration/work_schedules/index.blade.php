@extends('layouts.app')

@section('title', 'Lịch công tác')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Lịch công tác</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lịch công tác</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            @include('components.leader-schedule-card', [
                'leaderSchedule' => $leaderSchedule ?? collect(),
                'scheduleWeekRange' => $scheduleWeekRange ?? null,
            ])
        </div>
    </div>

    @if ($canManageSchedule)
        @php
            $timeOfDayLabels = [
                \App\Models\WorkSchedule::TIME_OF_DAY_MORNING => 'Buổi sáng',
                \App\Models\WorkSchedule::TIME_OF_DAY_AFTERNOON => 'Buổi chiều',
            ];
            $nextWeekStart = $nextWeekRange[0] ?? null;
            $nextWeekEnd = $nextWeekRange[1] ?? null;
            $defaultManagerId = $defaultManagerId ?? null;
        @endphp

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateWeekForm" aria-expanded="false" aria-controls="updateWeekForm">
                        <i class="bi bi-pencil-square me-1"></i>Điều chỉnh lịch tuần
                    </button>
                    @if ($canCreateNextWeek)
                        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#createNextWeekForm" aria-expanded="false" aria-controls="createNextWeekForm">
                            <i class="bi bi-calendar-plus me-1"></i>Tạo mới lịch tuần tới
                        </button>
                    @endif
                </div>

                <div class="collapse mt-4" id="updateWeekForm">
                    <h5 class="mb-3">Điều chỉnh lịch trong tuần</h5>
                    <form method="POST" action="{{ route('work-schedules.update-week') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label">Chọn lịch cần điều chỉnh</label>
                            <select name="schedule_id" id="schedule_id" class="form-select @error('schedule_id') is-invalid @enderror" required>
                                <option value="">-- Chọn lịch --</option>
                                @forelse ($editableSchedules as $schedule)
                                    <option value="{{ $schedule->id }}"
                                        data-detail-url="{{ route('work-schedules.show', $schedule) }}"
                                        @selected(old('schedule_id') == $schedule->id)
                                    >
                                        {{ $schedule->scheduled_date?->format('d/m/Y') }} - {{ $timeOfDayLabels[$schedule->time_of_day] ?? $schedule->time_of_day }} - {{ $schedule->user?->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Không có lịch nào để điều chỉnh.</option>
                                @endforelse
                            </select>
                            @error('schedule_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-danger small mt-2 d-none" id="scheduleLoadError">Không tải được dữ liệu lịch. Vui lòng thử lại.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">Người phụ trách</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Chọn người phụ trách --</option>
                                @foreach ($assignableUsers as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id', $defaultManagerId) == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="scheduled_date" class="form-label">Ngày</label>
                            <input type="date" name="scheduled_date" id="scheduled_date" value="{{ old('scheduled_date') }}" class="form-control @error('scheduled_date') is-invalid @enderror" required>
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="time_of_day" class="form-label">Buổi</label>
                            <select name="time_of_day" id="time_of_day" class="form-select @error('time_of_day') is-invalid @enderror" required>
                                <option value="">-- Chọn buổi --</option>
                                @foreach ($timeOfDayLabels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('time_of_day') == $key)> {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('time_of_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="time_range" class="form-label">Thời gian</label>
                            <input type="text" name="time_range" id="time_range" value="{{ old('time_range') }}" class="form-control @error('time_range') is-invalid @enderror" placeholder="08:00 - 09:30">
                            @error('time_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="content" class="form-label">Nội dung</label>
                            <input type="text" name="content" id="content" value="{{ old('content') }}" class="form-control @error('content') is-invalid @enderror" required>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="location" class="form-label">Địa điểm</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="Phòng họp 101">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Thành phần tham dự...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

                @if ($canCreateNextWeek)
                    <div class="collapse mt-4" id="createNextWeekForm">
                        <h5 class="mb-3">Tạo mới lịch tuần tới</h5>

                        @if ($nextWeekStart && $nextWeekEnd)
                            <div class="alert alert-light border mb-3" role="alert">
                                Tuần tới: <strong>{{ $nextWeekStart->format('d/m') }}</strong> - <strong>{{ $nextWeekEnd->format('d/m/Y') }}</strong>
                            </div>
                        @endif

                        @php
                            $defaultNextDate = $nextWeekStart ? $nextWeekStart->format('Y-m-d') : '';
                            $oldEntries = old('entries');

                            if (! is_array($oldEntries) || empty($oldEntries)) {
                                $oldEntries = [[
                                    'user_id' => old('user_id', $defaultManagerId),
                                    'scheduled_date' => old('scheduled_date', $defaultNextDate),
                                    'time_of_day' => old('time_of_day'),
                                    'time_range' => old('time_range'),
                                    'content' => old('content'),
                                    'location' => old('location'),
                                    'notes' => old('notes'),
                                ]];
                            }
                        @endphp

                        <form method="POST" action="{{ route('work-schedules.create-next-week') }}">
                            @csrf

                            @error('entries')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div id="nextWeekEntries" class="d-grid gap-3">
                                @foreach ($oldEntries as $index => $entry)
                                    @php
                                        $entryIndex = is_numeric($index) ? (int) $index : $loop->index;
                                        $dateValue = $entry['scheduled_date'] ?? $defaultNextDate;
                                    @endphp
                                    <div class="next-week-entry card border rounded-3" data-entry-index="{{ $entryIndex }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Hoạt động #<span class="entry-order">{{ $loop->iteration }}</span></h6>
                                                <button type="button" class="btn btn-link text-danger p-0 small {{ $loop->first && count($oldEntries) === 1 ? 'd-none' : '' }}" data-remove-entry>
                                                    <i class="bi bi-x-circle me-1"></i>Xóa
                                                </button>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="entry_user_id_{{ $entryIndex }}" class="form-label" data-label-for="user_id">Người phụ trách</label>
                                                    <select name="entries[{{ $entryIndex }}][user_id]" id="entry_user_id_{{ $entryIndex }}" data-field="user_id" class="form-select @error('entries.' . $entryIndex . '.user_id') is-invalid @enderror" required>
                                                        <option value="">-- Chọn người phụ trách --</option>
                                                        @foreach ($assignableUsers as $user)
                                                            <option value="{{ $user->id }}" @selected(($entry['user_id'] ?? null) == $user->id)>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('entries.' . $entryIndex . '.user_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="entry_scheduled_date_{{ $entryIndex }}" class="form-label" data-label-for="scheduled_date">Ngày</label>
                                                    <input type="date" name="entries[{{ $entryIndex }}][scheduled_date]" id="entry_scheduled_date_{{ $entryIndex }}" data-field="scheduled_date" value="{{ $dateValue }}" class="form-control @error('entries.' . $entryIndex . '.scheduled_date') is-invalid @enderror" required>
                                                    @error('entries.' . $entryIndex . '.scheduled_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="entry_time_of_day_{{ $entryIndex }}" class="form-label" data-label-for="time_of_day">Buổi</label>
                                                    <select name="entries[{{ $entryIndex }}][time_of_day]" id="entry_time_of_day_{{ $entryIndex }}" data-field="time_of_day" class="form-select @error('entries.' . $entryIndex . '.time_of_day') is-invalid @enderror" required>
                                                        <option value="">-- Chọn buổi --</option>
                                                        @foreach ($timeOfDayLabels as $key => $label)
                                                            <option value="{{ $key }}" @selected(($entry['time_of_day'] ?? null) == $key)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('entries.' . $entryIndex . '.time_of_day')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="entry_time_range_{{ $entryIndex }}" class="form-label" data-label-for="time_range">Thời gian</label>
                                                    <input type="text" name="entries[{{ $entryIndex }}][time_range]" id="entry_time_range_{{ $entryIndex }}" data-field="time_range" value="{{ $entry['time_range'] ?? '' }}" class="form-control @error('entries.' . $entryIndex . '.time_range') is-invalid @enderror" placeholder="08:00 - 09:30">
                                                    @error('entries.' . $entryIndex . '.time_range')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label for="entry_content_{{ $entryIndex }}" class="form-label" data-label-for="content">Nội dung</label>
                                                    <input type="text" name="entries[{{ $entryIndex }}][content]" id="entry_content_{{ $entryIndex }}" data-field="content" value="{{ $entry['content'] ?? '' }}" class="form-control @error('entries.' . $entryIndex . '.content') is-invalid @enderror" required>
                                                    @error('entries.' . $entryIndex . '.content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label for="entry_location_{{ $entryIndex }}" class="form-label" data-label-for="location">Địa điểm</label>
                                                    <input type="text" name="entries[{{ $entryIndex }}][location]" id="entry_location_{{ $entryIndex }}" data-field="location" value="{{ $entry['location'] ?? '' }}" class="form-control @error('entries.' . $entryIndex . '.location') is-invalid @enderror" placeholder="Phòng họp 101">
                                                    @error('entries.' . $entryIndex . '.location')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label for="entry_notes_{{ $entryIndex }}" class="form-label" data-label-for="notes">Ghi chú</label>
                                                    <textarea name="entries[{{ $entryIndex }}][notes]" id="entry_notes_{{ $entryIndex }}" data-field="notes" rows="3" class="form-control @error('entries.' . $entryIndex . '.notes') is-invalid @enderror" placeholder="Thành phần tham dự...">{{ $entry['notes'] ?? '' }}</textarea>
                                                    @error('entries.' . $entryIndex . '.notes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-outline-secondary" id="addNextWeekEntry">
                                    <i class="bi bi-plus-circle me-1"></i>Thêm hoạt động
                                </button>

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Lưu lịch tuần tới
                                </button>
                            </div>

                            <template id="nextWeekEntryTemplate" data-default-date="{{ $defaultNextDate }}">
                                <div class="next-week-entry card border rounded-3" data-entry-index="__INDEX__">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Hoạt động #<span class="entry-order"></span></h6>
                                            <button type="button" class="btn btn-link text-danger p-0 small" data-remove-entry>
                                                <i class="bi bi-x-circle me-1"></i>Xóa
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" data-label-for="user_id">Người phụ trách</label>
                                                <select class="form-select" data-field="user_id" required>
                                                    <option value="">-- Chọn người phụ trách --</option>
                                                    @foreach ($assignableUsers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" data-label-for="scheduled_date">Ngày</label>
                                                <input type="date" class="form-control" data-field="scheduled_date" required>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" data-label-for="time_of_day">Buổi</label>
                                                <select class="form-select" data-field="time_of_day" required>
                                                    <option value="">-- Chọn buổi --</option>
                                                    @foreach ($timeOfDayLabels as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" data-label-for="time_range">Thời gian</label>
                                                <input type="text" class="form-control" data-field="time_range" placeholder="08:00 - 09:30">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" data-label-for="content">Nội dung</label>
                                                <input type="text" class="form-control" data-field="content" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" data-label-for="location">Địa điểm</label>
                                                <input type="text" class="form-control" data-field="location" placeholder="Phòng họp 101">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" data-label-for="notes">Ghi chú</label>
                                                <textarea rows="3" class="form-control" data-field="notes" placeholder="Thành phần tham dự..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const scheduleSelect = document.getElementById('schedule_id');
                    const scheduleErrorEl = document.getElementById('scheduleLoadError');
                    const mapping = {
                        user_id: document.getElementById('user_id'),
                        scheduled_date: document.getElementById('scheduled_date'),
                        time_of_day: document.getElementById('time_of_day'),
                        content: document.getElementById('content'),
                        time_range: document.getElementById('time_range'),
                        location: document.getElementById('location'),
                        notes: document.getElementById('notes'),
                    };

                    const toggleScheduleFields = (disabled) => {
                        Object.values(mapping).forEach((element) => {
                            if (! element) {
                                return;
                            }

                            element.toggleAttribute('disabled', Boolean(disabled));
                        });
                    };

                    const fillScheduleFields = (data) => {
                        Object.entries(mapping).forEach(([key, element]) => {
                            if (! element) {
                                return;
                            }

                            element.value = data?.[key] ?? '';
                        });
                    };

                    const clearScheduleFields = () => fillScheduleFields(null);

                    if (scheduleSelect) {
                        scheduleSelect.addEventListener('change', function () {
                            const selected = scheduleSelect.options[scheduleSelect.selectedIndex];
                            const detailUrl = selected?.dataset?.detailUrl;

                            if (scheduleErrorEl) {
                                scheduleErrorEl.classList.add('d-none');
                            }

                            if (! detailUrl) {
                                clearScheduleFields();
                                return;
                            }

                            toggleScheduleFields(true);

                            fetch(detailUrl, {
                                headers: {
                                    'Accept': 'application/json',
                                },
                            })
                                .then((response) => {
                                    if (! response.ok) {
                                        throw new Error('Failed to fetch schedule');
                                    }

                                    return response.json();
                                })
                                .then((data) => {
                                    fillScheduleFields(data);
                                })
                                .catch(() => {
                                    clearScheduleFields();
                                    if (scheduleErrorEl) {
                                        scheduleErrorEl.classList.remove('d-none');
                                    }
                                })
                                .finally(() => {
                                    toggleScheduleFields(false);
                                });
                        });

                        if (scheduleSelect.value) {
                            scheduleSelect.dispatchEvent(new Event('change'));
                        }
                    }

                    const entriesContainer = document.getElementById('nextWeekEntries');
                    const entryTemplate = document.getElementById('nextWeekEntryTemplate');
                    const addEntryButton = document.getElementById('addNextWeekEntry');

                    if (! entriesContainer || ! entryTemplate) {
                        return;
                    }

                    const getCurrentEntryIndices = () => Array.from(entriesContainer.querySelectorAll('.next-week-entry'))
                        .map((entry) => Number(entry.dataset.entryIndex || 0));

                    const getNextEntryIndex = () => {
                        const indices = getCurrentEntryIndices();
                        if (indices.length === 0) {
                            return 0;
                        }

                        return Math.max(...indices) + 1;
                    };

                    const updateEntryOrders = () => {
                        const entries = entriesContainer.querySelectorAll('.next-week-entry');
                        entries.forEach((entry, order) => {
                            const orderEl = entry.querySelector('.entry-order');
                            if (orderEl) {
                                orderEl.textContent = order + 1;
                            }

                            const removeBtn = entry.querySelector('[data-remove-entry]');
                            if (removeBtn) {
                                removeBtn.classList.toggle('d-none', entries.length === 1 && order === 0);
                            }
                        });
                    };

                    const applyFieldMetadata = (entryElement, index) => {
                        entryElement.dataset.entryIndex = index;

                        entryElement.querySelectorAll('[data-field]').forEach((input) => {
                            const field = input.dataset.field;
                            const fieldId = `entry_${field}_${index}`;
                            input.name = `entries[${index}][${field}]`;
                            input.id = fieldId;
                            input.classList.remove('is-invalid');

                            if (input.tagName === 'TEXTAREA') {
                                input.value = input.value || '';
                            }

                            const label = entryElement.querySelector(`[data-label-for="${field}"]`);
                            if (label) {
                                label.setAttribute('for', fieldId);
                            }
                        });
                    };

                    const extractEntryValues = (entryElement) => {
                        const values = {};
                        entryElement.querySelectorAll('[data-field]').forEach((input) => {
                            const field = input.dataset.field;
                            values[field] = input.value;
                        });

                        return values;
                    };

                    const addEntry = (prefill = {}) => {
                        const index = getNextEntryIndex();
                        const clone = entryTemplate.content.cloneNode(true);
                        const entryElement = clone.querySelector('.next-week-entry');

                        if (! entryElement) {
                            return;
                        }

                        applyFieldMetadata(entryElement, index);

                        entryElement.querySelectorAll('[data-field]').forEach((input) => {
                            const field = input.dataset.field;
                            const defaultDate = entryTemplate.dataset.defaultDate || '';

                            let value = prefill[field];

                            if (value === undefined || value === null) {
                                if (field === 'scheduled_date') {
                                    value = defaultDate;
                                } else if (field === 'content' || field === 'notes') {
                                    value = '';
                                }
                            }

                            if (value !== undefined && value !== null) {
                                if (input.tagName === 'TEXTAREA') {
                                    input.value = value;
                                } else {
                                    input.value = value;
                                }
                            } else if (input.tagName === 'TEXTAREA') {
                                input.value = '';
                            } else {
                                input.value = '';
                            }
                        });

                        entriesContainer.appendChild(entryElement);
                        updateEntryOrders();
                    };

                    if (addEntryButton) {
                        addEntryButton.addEventListener('click', function () {
                            const currentEntries = entriesContainer.querySelectorAll('.next-week-entry');
                            let prefill = {};

                            if (currentEntries.length > 0) {
                                const lastEntry = currentEntries[currentEntries.length - 1];
                                prefill = extractEntryValues(lastEntry);
                                prefill.content = '';
                                prefill.notes = '';
                            }

                            addEntry(prefill);
                        });
                    }

                    entriesContainer.addEventListener('click', function (event) {
                        const removeButton = event.target.closest('[data-remove-entry]');
                        if (! removeButton) {
                            return;
                        }

                        const entry = removeButton.closest('.next-week-entry');
                        if (! entry) {
                            return;
                        }

                        entry.remove();
                        updateEntryOrders();
                    });

                    updateEntryOrders();
                });
            </script>
        @endpush
    @endif
@endsection