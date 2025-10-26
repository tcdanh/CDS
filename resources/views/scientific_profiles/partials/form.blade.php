@csrf
@method('PUT')

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Thông tin cá nhân</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="full_name">Họ và tên</label>
            <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $info->full_name) }}" required>
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="alternate_name">Tên gọi khác</label>
            <input type="text" id="alternate_name" name="alternate_name" class="form-control @error('alternate_name') is-invalid @enderror" value="{{ old('alternate_name', $info->alternate_name) }}">
            @error('alternate_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="birth_date">Ngày sinh</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($info->birth_date)->format('Y-m-d')) }}">
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="gender">Giới tính</label>
            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                <option value="">-- Chọn --</option>
                <option value="male" @selected(old('gender', $info->gender) === 'male')>Nam</option>
                <option value="female" @selected(old('gender', $info->gender) === 'female')>Nữ</option>
                <option value="other" @selected(old('gender', $info->gender) === 'other')>Khác</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="birth_place">Nơi sinh</label>
            <input type="text" id="birth_place" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place', $info->birth_place) }}">
            @error('birth_place')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="hometown">Quê quán</label>
            <input type="text" id="hometown" name="hometown" class="form-control @error('hometown') is-invalid @enderror" value="{{ old('hometown', $info->hometown) }}">
            @error('hometown')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="residence">Nơi ở hiện tại</label>
            <input type="text" id="residence" name="residence" class="form-control @error('residence') is-invalid @enderror" value="{{ old('residence', $info->residence) }}">
            @error('residence')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="ethnicity">Dân tộc</label>
            <input type="text" id="ethnicity" name="ethnicity" class="form-control @error('ethnicity') is-invalid @enderror" value="{{ old('ethnicity', $info->ethnicity) }}">
            @error('ethnicity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="religion">Tôn giáo</label>
            <input type="text" id="religion" name="religion" class="form-control @error('religion') is-invalid @enderror" value="{{ old('religion', $info->religion) }}">
            @error('religion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="cccd_number">Số CCCD</label>
            <input type="text" id="cccd_number" name="cccd_number" class="form-control @error('cccd_number') is-invalid @enderror" value="{{ old('cccd_number', $info->cccd_number) }}">
            @error('cccd_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="cccd_issued_date">Ngày cấp CCCD</label>
            <input type="date" id="cccd_issued_date" name="cccd_issued_date" class="form-control @error('cccd_issued_date') is-invalid @enderror" value="{{ old('cccd_issued_date', optional($info->cccd_issued_date)->format('Y-m-d')) }}">
            @error('cccd_issued_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="tax_code">Mã số thuế</label>
            <input type="text" id="tax_code" name="tax_code" class="form-control @error('tax_code') is-invalid @enderror" value="{{ old('tax_code', $info->tax_code) }}">
            @error('tax_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="phone_number">Số điện thoại</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $info->phone_number) }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $info->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="avatar">Ảnh đại diện</label>
            <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="health_insurance_number">Số BHYT</label>
            <input type="text" id="health_insurance_number" name="health_insurance_number" class="form-control @error('health_insurance_number') is-invalid @enderror" value="{{ old('health_insurance_number', $info->health_insurance_number) }}">
            @error('health_insurance_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="social_insurance_number">Số BHXH</label>
            <input type="text" id="social_insurance_number" name="social_insurance_number" class="form-control @error('social_insurance_number') is-invalid @enderror" value="{{ old('social_insurance_number', $info->social_insurance_number) }}">
            @error('social_insurance_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Thông tin công tác</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="employment_start_date">Ngày vào cơ quan</label>
            <input type="date" id="employment_start_date" name="employment_start_date" class="form-control @error('employment_start_date') is-invalid @enderror" value="{{ old('employment_start_date', optional($info->employment_start_date)->format('Y-m-d')) }}">
            @error('employment_start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="organization_name">Tên cơ quan</label>
            <input type="text" id="organization_name" name="organization_name" class="form-control @error('organization_name') is-invalid @enderror" value="{{ old('organization_name', $info->organization_name) }}">
            @error('organization_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="contract_type">Loại hợp đồng</label>
            <input type="text" id="contract_type" name="contract_type" class="form-control @error('contract_type') is-invalid @enderror" value="{{ old('contract_type', $info->contract_type) }}">
            @error('contract_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="main_job_title">Công việc chính/Chức vụ cao nhất</label>
            <input type="text" id="main_job_title" name="main_job_title" class="form-control @error('main_job_title') is-invalid @enderror" value="{{ old('main_job_title', $info->main_job_title) }}">
            @error('main_job_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="professional_title">Chức danh nghề nghiệp/Ngạch viên chức</label>
            <input type="text" id="professional_title" name="professional_title" class="form-control @error('professional_title') is-invalid @enderror" value="{{ old('professional_title', $info->professional_title) }}">
            @error('professional_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="expertise">Sở trường công tác</label>
            <input type="text" id="expertise" name="expertise" class="form-control @error('expertise') is-invalid @enderror" value="{{ old('expertise', $info->expertise) }}">
            @error('expertise')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="previous_job">Nghề nghiệp trước khi được tuyển dụng</label>
            <input type="text" id="previous_job" name="previous_job" class="form-control @error('previous_job') is-invalid @enderror" value="{{ old('previous_job', $info->previous_job) }}">
            @error('previous_job')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Tổ chức - Đảng - Đoàn</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="youth_union_joined_at">Ngày vào Đoàn TNCS</label>
            <input type="date" id="youth_union_joined_at" name="youth_union_joined_at" class="form-control @error('youth_union_joined_at') is-invalid @enderror" value="{{ old('youth_union_joined_at', optional($info->youth_union_joined_at)->format('Y-m-d')) }}">
            @error('youth_union_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="trade_union_joined_at">Ngày gia nhập Công đoàn</label>
            <input type="date" id="trade_union_joined_at" name="trade_union_joined_at" class="form-control @error('trade_union_joined_at') is-invalid @enderror" value="{{ old('trade_union_joined_at', optional($info->trade_union_joined_at)->format('Y-m-d')) }}">
            @error('trade_union_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="communist_party_joined_at">Ngày vào Đảng CSVN</label>
            <input type="date" id="communist_party_joined_at" name="communist_party_joined_at" class="form-control @error('communist_party_joined_at') is-invalid @enderror" value="{{ old('communist_party_joined_at', optional($info->communist_party_joined_at)->format('Y-m-d')) }}">
            @error('communist_party_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="army_enlisted_at">Ngày nhập ngũ</label>
            <input type="date" id="army_enlisted_at" name="army_enlisted_at" class="form-control @error('army_enlisted_at') is-invalid @enderror" value="{{ old('army_enlisted_at', optional($info->army_enlisted_at)->format('Y-m-d')) }}">
            @error('army_enlisted_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="army_discharged_at">Ngày xuất ngũ</label>
            <input type="date" id="army_discharged_at" name="army_discharged_at" class="form-control @error('army_discharged_at') is-invalid @enderror" value="{{ old('army_discharged_at', optional($info->army_discharged_at)->format('Y-m-d')) }}">
            @error('army_discharged_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="highest_army_rank">Quân hàm cao nhất</label>
            <input type="text" id="highest_army_rank" name="highest_army_rank" class="form-control @error('highest_army_rank') is-invalid @enderror" value="{{ old('highest_army_rank', $info->highest_army_rank) }}">
            @error('highest_army_rank')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Học vấn và danh hiệu</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="general_education_level">Trình độ giáo dục phổ thông</label>
            <input type="text" id="general_education_level" name="general_education_level" class="form-control @error('general_education_level') is-invalid @enderror" value="{{ old('general_education_level', $info->general_education_level) }}">
            @error('general_education_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="highest_academic_level">Trình độ chuyên môn cao nhất</label>
            <input type="text" id="highest_academic_level" name="highest_academic_level" class="form-control @error('highest_academic_level') is-invalid @enderror" value="{{ old('highest_academic_level', $info->highest_academic_level) }}">
            @error('highest_academic_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="highest_academic_year">Năm đạt được</label>
            <input type="number" min="1900" max="2100" id="highest_academic_year" name="highest_academic_year" class="form-control @error('highest_academic_year') is-invalid @enderror" value="{{ old('highest_academic_year', $info->highest_academic_year) }}">
            @error('highest_academic_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold" for="graduation_major">Ngành tốt nghiệp</label>
            <input type="text" id="graduation_major" name="graduation_major" class="form-control @error('graduation_major') is-invalid @enderror" value="{{ old('graduation_major', $info->graduation_major) }}">
            @error('graduation_major')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="state_honors">Danh hiệu nhà nước phong tặng</label>
            <input type="text" id="state_honors" name="state_honors" class="form-control @error('state_honors') is-invalid @enderror" value="{{ old('state_honors', $info->state_honors) }}">
            @error('state_honors')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="state_honors_year">Năm phong</label>
            <input type="number" min="1900" max="2100" id="state_honors_year" name="state_honors_year" class="form-control @error('state_honors_year') is-invalid @enderror" value="{{ old('state_honors_year', $info->state_honors_year) }}">
            @error('state_honors_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="academic_title">Học hàm</label>
            <input type="text" id="academic_title" name="academic_title" class="form-control @error('academic_title') is-invalid @enderror" value="{{ old('academic_title', $info->academic_title) }}">
            @error('academic_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="academic_title_year">Năm học hàm</label>
            <input type="number" min="1900" max="2100" id="academic_title_year" name="academic_title_year" class="form-control @error('academic_title_year') is-invalid @enderror" value="{{ old('academic_title_year', $info->academic_title_year) }}">
            @error('academic_title_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="professor_council">Hội đồng giáo sư</label>
            <input type="text" id="professor_council" name="professor_council" class="form-control @error('professor_council') is-invalid @enderror" value="{{ old('professor_council', $info->professor_council) }}">
            @error('professor_council')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Sức khỏe và thể chất</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="health_status">Tình trạng sức khoẻ</label>
            <input type="text" id="health_status" name="health_status" class="form-control @error('health_status') is-invalid @enderror" value="{{ old('health_status', $info->health_status) }}">
            @error('health_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="blood_group">Nhóm máu</label>
            <input type="text" id="blood_group" name="blood_group" class="form-control @error('blood_group') is-invalid @enderror" value="{{ old('blood_group', $info->blood_group) }}">
            @error('blood_group')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="height">Chiều cao</label>
            <input type="text" id="height" name="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height', $info->height) }}">
            @error('height')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="weight">Cân nặng</label>
            <input type="text" id="weight" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $info->weight) }}">
            @error('weight')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4">
    <h5 class="fw-bold text-primary mb-3">Lĩnh vực chuyên môn</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="teaching_field">Lĩnh vực giảng dạy</label>
            <input type="text" id="teaching_field" name="teaching_field" class="form-control @error('teaching_field') is-invalid @enderror" value="{{ old('teaching_field', $info->teaching_field) }}">
            @error('teaching_field')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="research_field">Lĩnh vực nghiên cứu</label>
            <input type="text" id="research_field" name="research_field" class="form-control @error('research_field') is-invalid @enderror" value="{{ old('research_field', $info->research_field) }}">
            @error('research_field')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save me-1"></i>
        Lưu thông tin
    </button>
</div>