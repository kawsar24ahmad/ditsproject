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
                                <li class="breadcrumb-item active"><a href="{{ route('admin.media.index') }}">Media</a></li>
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
                        <h4 class="mb-0">মিডিয়া আপডেট করুন</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"  value="{{ old('title', $media->title) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" onchange="toggleFields(this.value)" required>
                                    <option value="youtube" {{ old('type', $media->type) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                    <option value="audio" {{ old('type', $media->type) == 'audio' ? 'selected' : '' }}>Audio</option>
                                </select>
                            </div>

                            <div class="mb-3 {{ old('type', $media->type) !== 'youtube' ? 'd-none' : '' }}" id="youtube_field">
                                <label class="form-label">YouTube Link</label>
                                <input type="url" name="youtube_link" class="form-control" value="{{ old('youtube_link', $media->youtube_link) }}">
                            </div>

                            <div class="mb-3 {{ old('type', $media->type) !== 'audio' ? 'd-none' : '' }}" id="audio_field">
                                <label class="form-label">Audio File</label>
                                @if($media->audio_file)
                                    <p class="mt-1">বর্তমান অডিও: <a href="{{ asset($media->audio_file) }}" target="_blank">শুনুন</a></p>
                                @endif
                                <input type="file" name="audio_file" class="form-control">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update
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
    function toggleFields(type) {
        $('#youtube_field').toggleClass('d-none', type !== 'youtube');
        $('#audio_field').toggleClass('d-none', type !== 'audio');
    }

    $(document).ready(function () {
        const currentType = '{{ old('type', $media->type) }}';
        toggleFields(currentType);
    });
</script>
@endsection
