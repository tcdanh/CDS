@extends('layouts.app')

@section('title', 'Đơn xin nghỉ phép')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Đơn xin nghỉ phép</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Administration</li>
                        <li class="breadcrumb-item active" aria-current="page">Đơn xin nghỉ phép</li>
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Danh sách đơn nghỉ phép</h5>
                <small class="text-muted">Theo dõi nhanh tiến độ kiểm tra và phê duyệt</small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaveRequestModal">
                <i class="bi bi-plus-circle me-1"></i>Tạo đơn xin nghỉ phép
            </button>
        </div>
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nhân sự</th>
                        <th>Ngày nộp</th>
                        <th class="text-center">Số ngày</th>
                        <th>Từ ngày</th>
                        <th>Đến ngày</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leaveRequest)
                        @php
                            $statusClass = $statusStyles[$leaveRequest->status] ?? 'bg-secondary';
                            $allowedStatuses = $availableStatuses[$leaveRequest->id] ?? [];
                            $formId = 'leaveRequestAction' . $leaveRequest->id;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $leaveRequest->full_name }}</strong>
                                <div class="text-muted small">{{ $leaveRequest->user?->email }}</div>
                            </td>
                            <td>{{ optional($leaveRequest->created_at)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ number_format((float) $leaveRequest->days_requested, 2) }}</td>
                            <td>{{ optional($leaveRequest->start_date)->format('d/m/Y') }}</td>
                            <td>{{ optional($leaveRequest->end_date)->format('d/m/Y') }}</td>
                            <td style="max-width: 220px; white-space: pre-wrap;">{{ $leaveRequest->reason }}</td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabels[$leaveRequest->status] ?? $leaveRequest->status }}</span>
                            </td>
                            <td style="max-width: 180px; white-space: pre-wrap;">{{ $leaveRequest->notes ?? '---' }}</td>
                            <td class="text-end">
                                @if (! empty($allowedStatuses))
                                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $formId }}" aria-expanded="false" aria-controls="{{ $formId }}">
                                        <i class="bi bi-pencil-square me-1"></i>Cập nhật
                                    </button>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                        </tr>
                        @if (! empty($allowedStatuses))
                            <tr class="collapse" id="{{ $formId }}">
                                <td colspan="9" class="bg-light">
                                    <form method="POST" action="{{ route('leave-requests.update-status', $leaveRequest) }}" class="row g-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-4">
                                            <label for="status-{{ $leaveRequest->id }}" class="form-label">Trạng thái</label>
                                            <select id="status-{{ $leaveRequest->id }}" name="status" class="form-select" required>
                                                @foreach ($allowedStatuses as $status)
                                                    <option value="{{ $status }}" @selected($leaveRequest->status === $status)>
                                                        {{ $statusLabels[$status] ?? $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="notes-{{ $leaveRequest->id }}" class="form-label">Ghi chú</label>
                                            <textarea name="notes" id="notes-{{ $leaveRequest->id }}" rows="2" class="form-control" placeholder="Nhập ghi chú thêm (nếu có)">{{ old('notes', $leaveRequest->notes) }}</textarea>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-save me-1"></i>Lưu
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Hiện chưa có đơn xin nghỉ phép nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div>
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="createLeaveRequestModal" tabindex="-1" aria-labelledby="createLeaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLeaveRequestModalLabel">Tạo đơn xin nghỉ phép</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('leave-requests.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', auth()->user()?->name) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Từ ngày</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Đến ngày</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="days_requested" class="form-label">Số ngày xin nghỉ</label>
                                <input type="number" step="0.5" min="0.5" class="form-control" id="days_requested" name="days_requested" value="{{ old('days_requested') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="reason" class="form-label">Lý do</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label">Ghi chú (không bắt buộc)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Thông tin bổ sung nếu cần">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Gửi đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection