@extends('layouts.app')

@section('title', 'Quá trình đào tạo')

@section('content')
@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $personalRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $familyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $historyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $workRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];

    $records = optional($info)->trainingRecords ?? collect();
    $grouped = $records instanceof \Illuminate\Support\Collection ? $records->groupBy('category') : collect();

    $formalTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_FORMAL_TRAINING, []));
    $professionalTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_PROFESSIONAL_DEVELOPMENT, []));
    $managementTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_MANAGEMENT_TRAINING, []));
    $politicalTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_POLITICAL_THEORY, []));
    $defenseTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_NATIONAL_DEFENSE, []));
    $languageTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_FOREIGN_LANGUAGE, []));
    $itTrainings = collect($grouped->get(\App\Models\TrainingRecord::CATEGORY_INFORMATICS, []));
@endphp

@if (session('status') === 'personal-info-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        Lưu thông tin cá nhân thành công.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
    <div class="d-flex flex-wrap gap-2">
        @if ($canManageProfiles)
            <a href="{{ route('scientific-profiles.training') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách đào tạo
            </a>
        @endif
        <a href="{{ route('scientific-profiles.show', $personalRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-person-vcard me-1"></i>
            Xem thông tin cá nhân
        </a>
        <a href="{{ route('scientific-profiles.family', $familyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>
            Xem quan hệ gia đình
        </a>
        <a href="{{ route('scientific-profiles.history', $historyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-text me-1"></i>
            Xem lịch sử bản thân
        </a>
        <a href="{{ route('scientific-profiles.work', $workRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-briefcase me-1"></i>
            Quá trình công tác
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>
            Cập nhật hồ sơ
        </a>
    @endif
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <img src="{{ $info->avatar_url }}" alt="Ảnh đại diện" class="rounded-circle mb-3" width="120" height="120">
                <h5 class="fw-bold mb-1">{{ $info->full_name }}</h5>
                <p class="text-muted mb-4">Thông tin quá trình đào tạo & chứng chỉ</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-mortarboard"></i>
                        <div>
                            <div class="fw-semibold">Tổng số mục</div>
                            <div>{{ $records->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-person-badge"></i>
                        <div>
                            <div class="fw-semibold">Chức vụ hiện tại</div>
                            <div>{{ $info->main_job_title ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-envelope-at"></i>
                        <div>
                            <div class="fw-semibold">Email</div>
                            <div>{{ $info->email ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Quá trình đào tạo (từ trung cấp trở lên)</h4>
                <span class="badge text-bg-light text-dark">{{ $formalTrainings->count() }} mục</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">STT</th>
                                <th style="width: 140px;">Thời gian</th>
                                <th>Cơ sở đào tạo</th>
                                <th>Chuyên ngành</th>
                                <th>Hình thức</th>
                                <th>Văn bằng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($formalTrainings as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->timeframe ?? '—' }}</td>
                                    <td>{{ $record->institution ?? '—' }}</td>
                                    <td>{{ $record->major ?? '—' }}</td>
                                    <td>{{ $record->training_form ?? '—' }}</td>
                                    <td>{{ $record->qualification ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa cập nhật quá trình đào tạo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Bồi dưỡng nghiệp vụ</h4>
                        <span class="badge text-bg-light text-dark">{{ $professionalTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Chương trình</th>
                                        <th>Chứng chỉ</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($professionalTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->program_name ?? '—' }}</td>
                                            <td>{{ $record->certificate ?? '—' }}</td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật khóa bồi dưỡng nghiệp vụ.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Bồi dưỡng quản lý</h4>
                        <span class="badge text-bg-light text-dark">{{ $managementTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Chương trình</th>
                                        <th>Chứng chỉ</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($managementTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->program_name ?? '—' }}</td>
                                            <td>{{ $record->certificate ?? '—' }}</td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật khóa bồi dưỡng quản lý.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Lý luận chính trị</h4>
                        <span class="badge text-bg-light text-dark">{{ $politicalTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Trình độ</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm tốt nghiệp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($politicalTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->level ?? '—' }}</td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Chưa cập nhật thông tin lý luận chính trị.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">An ninh quốc phòng</h4>
                        <span class="badge text-bg-light text-dark">{{ $defenseTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Chương trình</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($defenseTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->program_name ?? '—' }}</td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Chưa cập nhật thông tin an ninh quốc phòng.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Ngoại ngữ</h4>
                        <span class="badge text-bg-light text-dark">{{ $languageTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Ngoại ngữ</th>
                                        <th>Trình độ/Chứng chỉ</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($languageTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->language ?? '—' }}</td>
                                            <td>
                                                {{ collect([$record->level, $record->certificate])->filter()->implode(' - ') ?: '—' }}
                                            </td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật chứng chỉ ngoại ngữ.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Tin học</h4>
                        <span class="badge text-bg-light text-dark">{{ $itTrainings->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">STT</th>
                                        <th>Chương trình/Kỹ năng</th>
                                        <th>Trình độ/Chứng chỉ</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th style="width: 120px;" class="text-center">Năm đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($itTrainings as $index => $record)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $record->program_name ?? '—' }}</td>
                                            <td>
                                                {{ collect([$record->level, $record->certificate])->filter()->implode(' - ') ?: '—' }}
                                            </td>
                                            <td>{{ $record->institution ?? '—' }}</td>
                                            <td class="text-center">{{ $record->year_awarded ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật chứng chỉ tin học.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection