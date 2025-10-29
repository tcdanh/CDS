@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')

@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $familyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $historyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $trainingRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $workRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
@endphp

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
    <div class="d-flex gap-2">
        @if ($canManageProfiles)
            <a href="{{ route('scientific-profiles.show') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách hồ sơ
            </a>
        @endif
        <a href="{{ route('scientific-profiles.family', $familyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>
            Xem quan hệ gia đình
        </a>
        <a href="{{ route('scientific-profiles.history', $historyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-text me-1"></i>
            Xem lịch sử bản thân
        </a>
        <a href="{{ route('scientific-profiles.training', $trainingRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-mortarboard me-1"></i>
            Quá trình đào tạo
        </a>
        <a href="{{ route('scientific-profiles.work', $workRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-briefcase me-1"></i>
            Quá trình công tác
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>
            Cập nhật thông tin
        </a>
    @endif
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                @php
                    $avatarUrl = $info->avatar_path
                        ? asset($info->avatar_path)
                        : asset('images/profile-placeholder.svg');
                    $genderLabel = match ($info->gender) {
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                        default => 'Chưa cập nhật',
                    };
                @endphp
                <img src="{{ $avatarUrl }}" class="rounded-circle mb-3" alt="Ảnh đại diện" width="120" height="120">
                <h5 class="fw-bold mb-1">{{ $info->full_name }}</h5>
                <p class="text-muted mb-3">{{ $info->main_job_title ?? 'Cập nhật công việc chính' }}</p>
                <dl class="row small text-start mb-4">
                    <dt class="col-5 text-muted">Giới tính</dt>
                    <dd class="col-7">{{ $genderLabel }}</dd>
                    <dt class="col-5 text-muted">Ngày sinh</dt>
                    <dd class="col-7">{{ optional($info->birth_date)->format('d/m/Y') ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Liên hệ</dt>
                    <dd class="col-7">{{ $info->phone_number ?? '—' }}</dd>
                </dl>
                <a href="{{ route('scientific-profiles.edit') }}" class="btn btn-primary w-100">
                    <i class="bi bi-pencil-square me-1"></i>
                    Cập nhật thông tin
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        @php
            $formatDate = fn($value) => $value ? $value->format('d/m/Y') : '—';
            $formatYear = fn($value) => $value ?: '—';
        @endphp

        @if (session('status') === 'personal-info-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                Lưu thông tin cá nhân thành công.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">1. Thông tin cá nhân</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Họ và tên</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->full_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Tên gọi khác</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->alternate_name ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Ngày sinh</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->birth_date) }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Giới tính</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $genderLabel }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Số CCCD</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->cccd_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Nơi sinh</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->birth_place ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Quê quán</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->hometown ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Nơi ở</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->residence ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Dân tộc</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->ethnicity ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Tôn giáo</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->religion ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Ngày cấp CCCD</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->cccd_issued_date) }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Mã số thuế</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->tax_code ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Số BHYT</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->health_insurance_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Số BHXH</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->social_insurance_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Số điện thoại</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->phone_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Email</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->email ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">2. Thông tin công tác</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Ngày vào cơ quan</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->employment_start_date) }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Tên cơ quan</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->organization_name ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Loại hợp đồng</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->contract_type ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Công việc chính/Chức vụ cao nhất</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->main_job_title ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Chức danh nghề nghiệp/Ngạch viên chức</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->professional_title ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Sở trường công tác</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->expertise ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Nghề nghiệp trước khi tuyển dụng</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->previous_job ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">3. Tổ chức - Đảng - Đoàn</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Ngày vào Đoàn TNCS</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->youth_union_joined_at) }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Ngày gia nhập Công đoàn</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->trade_union_joined_at) }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Ngày vào Đảng CSVN</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->communist_party_joined_at) }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Quân hàm cao nhất</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->highest_army_rank ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Ngày nhập ngũ</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->army_enlisted_at) }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Ngày xuất ngũ</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatDate($info->army_discharged_at) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">4. Học vấn và danh hiệu</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Trình độ giáo dục phổ thông</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->general_education_level ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Trình độ chuyên môn cao nhất</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->highest_academic_level ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Năm đạt được</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatYear($info->highest_academic_year) }}</p>
                    </div>
                    <div class="col-md-8">
                        <span class="text-muted text-uppercase small">Ngành tốt nghiệp</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->graduation_major ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Danh hiệu nhà nước phong tặng</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->state_honors ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Năm phong</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatYear($info->state_honors_year) }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Học hàm</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->academic_title ?? '—' }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Năm học hàm</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $formatYear($info->academic_title_year) }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted text-uppercase small">Hội đồng giáo sư</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->professor_council ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">5. Sức khỏe và thể chất</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Tình trạng sức khoẻ</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->health_status ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted text-uppercase small">Nhóm máu</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->blood_group ?? '—' }}</p>
                    </div>
                    <div class="col-md-2">
                        <span class="text-muted text-uppercase small">Chiều cao</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->height ?? '—' }}</p>
                    </div>
                    <div class="col-md-2">
                        <span class="text-muted text-uppercase small">Cân nặng</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->weight ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">6. Lĩnh vực chuyên môn</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Lĩnh vực giảng dạy</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->teaching_field ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted text-uppercase small">Lĩnh vực nghiên cứu</span>
                        <p class="fs-6 fw-semibold mb-0">{{ $info->research_field ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    Thông tin về gia đình và tài sản được hiển thị ở trang riêng. Sử dụng nút
                    <strong>"Xem quan hệ gia đình"</strong> để truy cập.
                </div>
            </div>
        </div>          
    </div>
</div>
@endsection