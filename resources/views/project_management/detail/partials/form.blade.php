@php
    $detail = $project->detail;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="contract_number" class="form-label">Số hợp đồng</label>
        <input type="text" name="contract_number" id="contract_number" class="form-control @error('contract_number') is-invalid @enderror" value="{{ old('contract_number', optional($detail)->contract_number) }}">
        @error('contract_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="contract_signed_at" class="form-label">Ngày ký hợp đồng</label>
        <input type="date" name="contract_signed_at" id="contract_signed_at" class="form-control @error('contract_signed_at') is-invalid @enderror" value="{{ old('contract_signed_at', optional(optional($detail)->contract_signed_at)->format('Y-m-d')) }}">
        @error('contract_signed_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="contract_storage_path" class="form-label">Nơi lưu số hợp đồng (file)</label>
        <input type="file" name="contract_storage_file" id="contract_storage_file" class="form-control @error('contract_storage_file') is-invalid @enderror">
        @error('contract_storage_file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($detail && $detail->contract_storage_path)
            <div class="form-text">
                Tệp hiện tại: <a href="{{ route('project-details.download', $project) }}">{{ basename($detail->contract_storage_path) }}</a>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label for="direct_labor_cost" class="form-label">Tiền công trực tiếp</label>
        <input type="number" step="0.01" name="direct_labor_cost" id="direct_labor_cost" class="form-control @error('direct_labor_cost') is-invalid @enderror" value="{{ old('direct_labor_cost', optional($detail)->direct_labor_cost) }}">
        @error('direct_labor_cost')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="material_cost" class="form-label">Tiền vật tư</label>
        <input type="number" step="0.01" name="material_cost" id="material_cost" class="form-control @error('material_cost') is-invalid @enderror" value="{{ old('material_cost', optional($detail)->material_cost) }}">
        @error('material_cost')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="other_cost" class="form-label">Tiền chi khác</label>
        <input type="number" step="0.01" name="other_cost" id="other_cost" class="form-control @error('other_cost') is-invalid @enderror" value="{{ old('other_cost', optional($detail)->other_cost) }}">
        @error('other_cost')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="management_cost" class="form-label">Tiền quản lý nhiệm vụ</label>
        <input type="number" step="0.01" name="management_cost" id="management_cost" class="form-control @error('management_cost') is-invalid @enderror" value="{{ old('management_cost', optional($detail)->management_cost) }}">
        @error('management_cost')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label d-block">Có gia hạn không?</label>
        @php
            $isExtended = old('is_extended', optional($detail)->is_extended ?? false);
        @endphp
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_extended" id="is_extended_yes" value="1" {{ $isExtended ? 'checked' : '' }}>
            <label class="form-check-label" for="is_extended_yes">Có</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_extended" id="is_extended_no" value="0" {{ ! $isExtended ? 'checked' : '' }}>
            <label class="form-check-label" for="is_extended_no">Không</label>
        </div>
        @error('is_extended')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="extension_details" class="form-label">Thông tin gia hạn (nếu có)</label>
        <textarea name="extension_details" id="extension_details" class="form-control @error('extension_details') is-invalid @enderror" rows="3">{{ old('extension_details', optional($detail)->extension_details) }}</textarea>
        @error('extension_details')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>