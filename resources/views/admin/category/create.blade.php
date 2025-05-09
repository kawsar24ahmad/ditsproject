@extends('admin.layouts.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
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
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{route('admin_categories.index')}}">User</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="basic-vertical-layouts">
                <form class="form form-vertical" action="{{route('admin_categories.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Category</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-12  col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="title"> title <span class="text-danger">*</span> </label>
                                                        <input type="text" class="form-control" name="title" value="{{old('title')}}" placeholder="Category title" required>
                                                        @error('title')
                                                        <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12  col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="slug"> slug <span class="text-danger">*</span> </label>
                                                        <input type="text" class="form-control" name="slug" value="{{old('slug')}}" placeholder="Category slug" required>
                                                        @error('slug')
                                                        <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12  col-md-6">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical"> Serial no <span class="text-danger">*</span> </label>
                                                        <input type="text" class="form-control" name="serial_no" value="{{old('serial no')}}" placeholder="serial_no" required>
                                                        @error('serial_no')
                                                        <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Save</button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    //GetSubCat
    var token = $("input[name=_token]").val();


    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
<script>
    $('#summernote').summernote({
        placeholder: 'Description Text here',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>
<script>
    $(document).on('click', '.add', function() {
        var html = '';
        html += '<tr>';
        html += '<td><input type="file" name="multiple_img[]" class="form-control"/></td>';
        html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="fa fa-minus-circle"></span></button></td></tr>';
        $('#UserImage').append(html);
    });
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
</script>
<script>
    $(document).ready(function() {

        $('#myCheckbox').change(function() {

            var checked = $(this).is(':checked');
            if (checked == true) {
                $('#self_input').attr('required', true);
                $('#parent_input').attr('required', true);
                $('#sibling_input').attr('required', true);
                $('#child_input').attr('required', true);
            }

        });
        $('#myCheckbox1').change(function() {

            var checked = $(this).is(':checked');
            if (checked == true) {
                $('#self_input').attr('required', false);
                $('#parent_input').attr('required', false);
                $('#sibling_input').attr('required', false);
                $('#child_input').attr('required', false);
            }

        });


    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
@endsection
