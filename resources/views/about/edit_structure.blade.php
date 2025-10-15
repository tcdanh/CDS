@extends('layouts.app')

@section('title', 'Chỉnh sửa Thành viên ban lãnh đạo')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">News Management</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('about.index') }}">About us</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Structure</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
        </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<div class="container py-4">
    <div class="card card-primary">
        <div class="card-header">Chỉnh sửa Thành viên</div>
        <div class="card-body">
            <form action="{{ route('about.structures.update', $structure->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Họ và tên</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $structure->name) }}">
                </div>

                <div class="mb-3">
                    <label>Vị trí</label>
                    <input type="text" name="position" class="form-control" required value="{{ old('position', $structure->position) }}">
                </div>

                <div class="mb-3">
                    <label>Thông tin</label>
                    <textarea name="description" class="form-control editor1" required>{{ old('description', $structure->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Ảnh đại diện hiện tại:</label><br>
                    @if ($structure->image)
                        <img src="{{ asset('images/intros/' . $structure->image) }}" width="200" class="mb-2">
                    @else
                        <p><em>Chưa có ảnh</em></p>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Chọn ảnh đại diện mới (nếu muốn)</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('about.index') }}" class="btn btn-secondary">Huỷ</a>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
  $(function () {
    bsCustomFileInput.init();
  });
</script>
@endpush
@push('scripts')
<script>
    document.querySelectorAll('.editor1').forEach((el) => {
        ClassicEditor
            .create(el)
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush