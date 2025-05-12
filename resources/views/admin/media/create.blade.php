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
                        <h4 class="mb-0">নতুন মিডিয়া যুক্ত করুন</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" onchange="toggleFields(this.value)" required>
                                    <option value="youtube">YouTube</option>
                                    <option value="audio">Audio</option>
                                </select>
                            </div>

                            <div class="mb-3" id="youtube_field">
                                <label class="form-label">YouTube Link</label>
                                <input type="url" name="youtube_link" class="form-control">
                            </div>

                            <div class="mb-3 d-none" id="audio_field">
                                <label class="form-label">Audio File</label>
                                <input type="file" name="audio_file" class="form-control">
                            </div>

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
    function toggleFields(type) {
        $('#youtube_field').toggleClass('d-none', type !== 'youtube');
        $('#audio_field').toggleClass('d-none', type !== 'audio');
    }

    $(document).ready(function () {
        // Optional: Keep selected type on page reload (if validation fails)
        const currentType = $('select[name="type"]').val();
        toggleFields(currentType);
    });
</script>
@endsection
