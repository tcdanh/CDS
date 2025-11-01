<div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="name_vi" class="form-label">Tên dự án (Tiếng Việt) <span class="text-danger">*</span></label>
            <input type="text" id="name_vi" name="name_vi" class="form-control" value="{{ old('name_vi', $project->name_vi ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label for="name_en" class="form-label">Project Name (English) <span class="text-danger">*</span></label>
            <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en', $project->name_en ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label for="industry_group" class="form-label">Thuộc nhóm ngành</label>
            <input type="text" id="industry_group" name="industry_group" class="form-control" value="{{ old('industry_group', $project->industry_group ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="research_type" class="form-label">Loại hình nghiên cứu</label>
            <input type="text" id="research_type" name="research_type" class="form-control" value="{{ old('research_type', $project->research_type ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="implementation_time" class="form-label">Thời gian thực hiện</label>
            <input type="text" id="implementation_time" name="implementation_time" class="form-control" value="{{ old('implementation_time', $project->implementation_time ?? '') }}" placeholder="Ví dụ: 01/2024 - 12/2025">
        </div>
        <div class="col-md-6">
            <label for="principal_investigator_id" class="form-label">Chủ nhiệm đề tài</label>
            <select id="principal_investigator_id" name="principal_investigator_id" class="form-select">
                <option value="">-- Chọn chủ nhiệm đề tài --</option>
                @foreach ($personalInfos as $personalInfo)
                    <option value="{{ $personalInfo->id }}" @selected(old('principal_investigator_id', $project->principal_investigator_id ?? '') == $personalInfo->id)>
                        {{ $personalInfo->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="science_secretary" class="form-label">Thư ký khoa học</label>
            <input type="text" id="science_secretary" name="science_secretary" class="form-control" value="{{ old('science_secretary', $project->science_secretary ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="total_budget" class="form-label">Tổng kinh phí (VND)</label>
            <input type="number" step="0.01" min="0" id="total_budget" name="total_budget" class="form-control" value="{{ old('total_budget', isset($project) ? $project->total_budget : null) }}" placeholder="Ví dụ: 150000000">
        </div>
        <div class="col-md-6">
            <label for="status" class="form-label">Tình trạng</label>
            <input type="text" id="status" name="status" class="form-control" value="{{ old('status', $project->status ?? '') }}">
        </div>
        <div class="col-12">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea id="notes" name="notes" class="form-control" rows="4">{{ old('notes', $project->notes ?? '') }}</textarea>
        </div>
    </div>
</div>
<div class="card-footer d-flex justify-content-between">
    <a href="{{ route('project-management.index') }}" class="btn btn-secondary">Quay lại</a>
    <button type="submit" class="btn btn-primary">Lưu</button>
</div>