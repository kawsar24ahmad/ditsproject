@extends('employee.layouts.app')

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
                                <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Home</a></li>

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="basic-vertical-layouts">
                <form class="form form-vertical" action="{{ route('employeeUsers.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Important for PUT method --}}
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Edit User</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <div class="row">

                                                {{-- Name --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" placeholder="User Name" required>
                                                        @error('name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Email --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email">Email <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" required>
                                                        @error('email')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Phone --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone" required>
                                                        @error('phone')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Password --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password">Password (leave blank if unchanged)</label>
                                                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                                                        @error('password')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Role --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="role">Role</label>
                                                        <select name="role" class="form-control">
                                                            <option read-only value="user" {{ old('user', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                                        </select>
                                                    </div>
                                                </div>

                            {{-- Facebook ID Link --}}
<div class="col-12">
    <div class="form-group">
        <label for="fb_id_link">Facebook ID Link</label>
        <input type="text" class="form-control" name="fb_id_link"
               value="{{ old('fb_id_link', $user->fb_id_link ?? '') }}"
               placeholder="Enter Facebook ID link">
        @error('fb_id_link')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

{{-- Facebook Page Link --}}
<div class="col-12">
    <div class="form-group">
        <label for="fb_page_link">Facebook Page Link</label>
        <input type="text" class="form-control" name="fb_page_link"
               value="{{ old('fb_page_link', $user->fb_page_link ?? '') }}"
               placeholder="Enter Facebook Page link">
        @error('fb_page_link')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

{{-- Starting Followers --}}
<div class="col-12">
    <div class="form-group">
        <label for="starting_followers">Starting Followers</label>
        <input type="number" class="form-control" name="starting_followers"
               value="{{ old('starting_followers', $user->starting_followers ?? '') }}"
               placeholder="Enter starting follower count">
        @error('starting_followers')
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

                        {{-- Submit Button --}}
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
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple, .js-example-basic-single').select2();

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

        $(document).on('click', '.add', function() {
            $('#UserImage').append(
                `<tr>
                    <td><input type="file" name="multiple_img[]" class="form-control"/></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove"><span class="fa fa-minus-circle"></span></button></td>
                </tr>`
            );
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
        });

        $('#myCheckbox').change(function() {
            let required = $(this).is(':checked');
            $('#self_input, #parent_input, #sibling_input, #child_input').attr('required', required);
        });

        $('#myCheckbox1').change(function() {
            let required = !$(this).is(':checked');
            $('#self_input, #parent_input, #sibling_input, #child_input').attr('required', required);
        });
    });
</script>
@endsection
