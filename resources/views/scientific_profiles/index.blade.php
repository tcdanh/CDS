@extends('layouts.app')

@php
    $mode = $mode ?? 'personal';
    $config = [
        'personal' => [
            'title' => 'Sơ yếu lý lịch nhân sự',
            'heading' => 'Danh sách sơ yếu lý lịch',
            'description' => 'Quản lý và xem hồ sơ của từng nhân sự',
            'toggle_route' => 'scientific-profiles.family',
            'toggle_icon' => 'bi-people',
            'toggle_label' => 'Xem quan hệ gia đình',
            'profile_route' => 'scientific-profiles.show',
            'profile_icon' => 'bi-person-badge',
            'action_route' => 'scientific-profiles.show',
            'action_icon' => 'bi-eye',
            'action_title' => 'Xem thông tin cá nhân',
        ],
        'family' => [
            'title' => 'Quan hệ gia đình',
            'heading' => 'Danh sách quan hệ gia đình',
            'description' => 'Xem và quản lý thông tin gia đình, tài sản nhà đất của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.family',
            'profile_icon' => 'bi-people',
            'action_route' => 'scientific-profiles.family',
            'action_icon' => 'bi-people',
            'action_title' => 'Xem thông tin gia đình',
        ],
        'history' => [
            'title' => 'Lịch sử bản thân',
            'heading' => 'Danh sách lịch sử bản thân',
            'description' => 'Xem và quản lý các mục lịch sử bản thân của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.history',
            'profile_icon' => 'bi-journal-text',
            'action_route' => 'scientific-profiles.history',
            'action_icon' => 'bi-journal-text',
            'action_title' => 'Xem lịch sử bản thân',
        ],
        'training' => [
            'title' => 'Quá trình đào tạo',
            'heading' => 'Danh sách quá trình đào tạo',
            'description' => 'Theo dõi các quá trình đào tạo, bồi dưỡng và chứng chỉ của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.training',
            'profile_icon' => 'bi-mortarboard',
            'action_route' => 'scientific-profiles.training',
            'action_icon' => 'bi-mortarboard',
            'action_title' => 'Xem quá trình đào tạo',
        ],
        'work' => [
            'title' => 'Quá trình công tác',
            'heading' => 'Danh sách quá trình công tác',
            'description' => 'Theo dõi quá trình công tác tại các đơn vị của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.work',
            'profile_icon' => 'bi-briefcase',
            'action_route' => 'scientific-profiles.work',
            'action_icon' => 'bi-briefcase',
            'action_title' => 'Xem quá trình công tác',
        ],
        'planning' => [
            'title' => 'Quy hoạch',
            'heading' => 'Danh sách quy hoạch',
            'description' => 'Theo dõi thông tin quy hoạch chức danh của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.planning',
            'profile_icon' => 'bi-diagram-3',
            'action_route' => 'scientific-profiles.planning',
            'action_icon' => 'bi-diagram-3',
            'action_title' => 'Xem thông tin quy hoạch',
        ],
        'teaching' => [
            'title' => 'Hoạt động giảng dạy',
            'heading' => 'Danh sách hoạt động giảng dạy',
            'description' => 'Theo dõi khối lượng giảng dạy và hướng dẫn của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.teaching',
            'profile_icon' => 'bi-easel2',
            'action_route' => 'scientific-profiles.teaching',
            'action_icon' => 'bi-easel2',
            'action_title' => 'Xem hoạt động giảng dạy',
        ],
        'research' => [
            'title' => 'Đề tài - Dự án',
            'heading' => 'Danh sách đề tài - dự án',
            'description' => 'Theo dõi quá trình tham gia đề tài, dự án khoa học của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.research',
            'profile_icon' => 'bi-collection',
            'action_route' => 'scientific-profiles.research',
            'action_icon' => 'bi-collection',
            'action_title' => 'Xem đề tài - dự án',
        ],
        'awards' => [
            'title' => 'Giải thưởng khoa học & giảng dạy',
            'heading' => 'Danh sách giải thưởng',
            'description' => 'Theo dõi các giải thưởng khoa học và giảng dạy của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.awards',
            'profile_icon' => 'bi-trophy',
            'action_route' => 'scientific-profiles.awards',
            'action_icon' => 'bi-trophy',
            'action_title' => 'Xem giải thưởng khoa học',
        ],
        'publications' => [
            'title' => 'Xuất bản & Sở hữu trí tuệ',
            'heading' => 'Danh sách công bố và SHTT',
            'description' => 'Theo dõi các công trình công bố và văn bằng sở hữu trí tuệ của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.publications',
            'profile_icon' => 'bi-journal-richtext',
            'action_route' => 'scientific-profiles.publications',
            'action_icon' => 'bi-journal-richtext',
            'action_title' => 'Xem xuất bản & SHTT',
        ],
        'compensation' => [
            'title' => 'Lương & phụ cấp',
            'heading' => 'Danh sách lương & phụ cấp',
            'description' => 'Theo dõi thông tin lương và phụ cấp của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.compensation',
            'profile_icon' => 'bi-cash-coin',
            'action_route' => 'scientific-profiles.compensation',
            'action_icon' => 'bi-cash-coin',
            'action_title' => 'Xem thông tin lương & phụ cấp',
        ],
        'recognition' => [
            'title' => 'Khen thưởng - Kỷ luật',
            'heading' => 'Danh sách khen thưởng - kỷ luật',
            'description' => 'Theo dõi thông tin khen thưởng và kỷ luật của từng nhân sự',
            'toggle_route' => 'scientific-profiles.show',
            'toggle_icon' => 'bi-person-badge',
            'toggle_label' => 'Xem thông tin cá nhân',
            'profile_route' => 'scientific-profiles.recognition',
            'profile_icon' => 'bi-award',
            'action_route' => 'scientific-profiles.recognition',
            'action_icon' => 'bi-award',
            'action_title' => 'Xem thông tin khen thưởng - kỷ luật',
        ],
    ];

    $config = $config[$mode] ?? $config['personal'];
@endphp

@section('title', $config['title'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center bg-white">
        <div>
            <h3 class="card-title mb-0">{{ $config['heading'] }}</h3>&nbsp;
            <p class="mb-0 text-muted small">{{ $config['description'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route($config['toggle_route'], ['user' => $currentUser->getKey()]) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi {{ $config['toggle_icon'] }} me-1"></i>
                {{ $config['toggle_label'] }}
            </a>
            <a href="{{ route($config['profile_route'], ['user' => $currentUser->getKey()]) }}" class="btn btn-outline-primary">
                <i class="bi {{ $config['profile_icon'] }} me-1"></i>
                Hồ sơ của tôi
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 80px;">STT</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col" style="width: 220px;">Cập nhật lúc</th>
                        <th scope="col" class="text-center" style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personalInfos as $index => $personalInfo)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $personalInfo->full_name ?? $personalInfo->user?->name ?? 'Chưa cập nhật' }}</div>
                                @if ($mode === 'family')
                                    @php
                                        $familyCount = $personalInfo->family_members_count ?? 0;
                                        $assetCount = $personalInfo->family_assets_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $familyCount }} thành viên gia đình
                                        <span class="mx-1">•</span>
                                        {{ $assetCount }} tài sản nhà đất
                                    </div>
                                @elseif ($mode === 'history')
                                    <div class="text-muted small">
                                        {{ $personalInfo->personalHistory ? 'Đã cập nhật lịch sử bản thân' : 'Chưa có dữ liệu lịch sử bản thân' }}
                                    </div>
                                @elseif ($mode === 'training')
                                    <div class="text-muted small">
                                        {{ $personalInfo->training_records_count ?? 0 }} mục đào tạo & chứng chỉ
                                    </div>
                                @elseif ($mode === 'work')
                                    <div class="text-muted small">
                                        {{ $personalInfo->work_experiences_count ?? 0 }} mục công tác
                                    </div>
                                @elseif ($mode === 'planning')
                                    <div class="text-muted small">
                                        {{ $personalInfo->planning_records_count ?? 0 }} mục quy hoạch
                                    </div>
                                @elseif ($mode === 'teaching')
                                    @php
                                        $teachingCount = $personalInfo->teaching_activities_count ?? 0;
                                        $supervisionCount = $personalInfo->supervision_activities_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $teachingCount }} mục giảng dạy
                                        <span class="mx-1">•</span>
                                        {{ $supervisionCount }} mục hướng dẫn
                                    </div>
                                @elseif ($mode === 'research')
                                    <div class="text-muted small">
                                        {{ $personalInfo->research_project_records_count ?? 0 }} đề tài - dự án
                                    </div>
                                @elseif ($mode === 'awards')
                                    <div class="text-muted small">
                                        {{ $personalInfo->scientific_awards_count ?? 0 }} giải thưởng khoa học
                                    </div>
                                @elseif ($mode === 'publications')
                                    @php
                                        $publicationCount = $personalInfo->scientific_publications_count ?? 0;
                                        $ipCount = $personalInfo->intellectual_property_records_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $publicationCount }} công bố KHCN
                                        <span class="mx-1">•</span>
                                        {{ $ipCount }} văn bằng SHTT
                                    </div>
                                @elseif ($mode === 'compensation')
                                    @php
                                        $salaryCount = $personalInfo->salary_records_count ?? 0;
                                        $allowanceCount = $personalInfo->allowance_records_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $salaryCount }} mục lương
                                        <span class="mx-1">•</span>
                                        {{ $allowanceCount }} mục phụ cấp
                                    </div>
                                @elseif ($mode === 'recognition')
                                    @php
                                        $rewardCount = $personalInfo->reward_records_count ?? 0;
                                        $disciplineCount = $personalInfo->discipline_records_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $rewardCount }} mục khen thưởng
                                        <span class="mx-1">•</span>
                                        {{ $disciplineCount }} mục kỷ luật
                                    </div>
                                @else
                                    <div class="text-muted small">{{ $personalInfo->main_job_title ?? '—' }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($personalInfo->updated_at)
                                    {{ $personalInfo->updated_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <a
                                    href="{{ route($config['action_route'], ['user' => $personalInfo->user_id]) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="{{ $config['action_title'] }}"
                                >
                                    <i class="bi {{ $config['action_icon'] }}"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Chưa có hồ sơ nào trong hệ thống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection