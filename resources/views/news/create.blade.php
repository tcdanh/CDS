@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- Card wrapper --}}
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create News Article</h3>
                    </div>

                    {{-- Form start --}}
                    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            {{-- Validation errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Title --}}
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>

                            {{-- Content --}}
                            <div class="form-group">
                                <label for="content">Content <span class="text-danger">*</span></label>
                                <!--textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea -->
                                <textarea name="content" id="editor" class="form-control" rows="15" style="height: 500px;">{{ old('content') }}</textarea>
                            </div>

                            {{-- Image --}}
                            <div class="form-group">
                                <label for="image">Image (optional)</label>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="image">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                            </div>
                        </div>

                        {{-- Form buttons --}}
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Publish</button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div> <!-- /card -->
            </div>
        </div>
    </div>
</section>
<!--script src="{{ asset('js/adminLTE/bs-custome-file-input.js') }}"></script-->
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
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush