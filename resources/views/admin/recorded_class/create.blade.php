@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('admin_categories.index') }}">User</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section class="container mt-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">নতুন রেকর্ডেড ক্লাস যুক্ত করুন</h4>
                    </div>
                    <div class="card-body">

                        {{-- Global Validation Error Alert --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops! Something went wrong.</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.storeRecordedClass') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- YouTube Link --}}
                            <div class="mb-3">
                                <label class="form-label">YouTube Link</label>
                                <input type="url" name="youtube_link" class="form-control @error('youtube_link') is-invalid @enderror" value="{{ old('youtube_link') }}">
                                @error('youtube_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- OR Divider --}}
                            <div class="text-center my-2">
                                <strong>OR</strong>
                            </div>

                            {{-- MP4 Video Upload --}}
                            <div class="mb-3">
                                <label class="form-label">Upload MP4 Video</label>
                                <input type="file" name="video_file" accept="video/mp4" class="form-control @error('video_file') is-invalid @enderror">
                                @error('video_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
    $(document).ready(function () {
        $('input[name="youtube_link"]').on('input', function () {
            $('input[name="video_file"]').prop('disabled', $(this).val().length > 0);
        });

        $('input[name="video_file"]').on('change', function () {
            $('input[name="youtube_link"]').prop('disabled', $(this).val().length > 0);
        });
    });
</script>
@endsection
