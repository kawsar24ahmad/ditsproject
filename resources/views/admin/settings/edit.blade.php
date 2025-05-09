@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Site Settings</h1>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">


            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name ?? '') }}" required>
                        @error('site_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="site_url" class="form-label">Site URL</label>
                        <input type="url" class="form-control @error('site_url') is-invalid @enderror" id="site_url" name="site_url" value="{{ old('site_url', $settings->site_url ?? '') }}" required>
                        @error('site_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                        @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(!empty($settings->logo))
                        <img width="150" src="{{ asset($settings->logo)}}" alt="Logo" class="img-thumbnail mt-2" style="max-height: 100px;">
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="favicon" class="form-label">Favicon</label>
                        <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" accept="image/*">
                        @error('favicon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(!empty($settings->favicon))
                        <img width="40" src="{{ asset($settings->favicon) }}" alt="Favicon" class="img-thumbnail mt-2" style="max-height: 50px;">
                        @endif
                    </div>

                </div>

                <hr class="my-4">

                <h3 class="mb-3">Payment Info</h3>

                <div class="row g-3">

                    <!-- Bkash Account No -->
                    <div class="col-md-6">
                        <label for="bkash_account_no" class="form-label">Bkash Account No</label>
                        <input type="text" class="form-control @error('bkash_account_no') is-invalid @enderror" id="bkash_account_no" name="bkash_account_no" value="{{ old('bkash_account_no', $settings->bkash_account_no ?? '') }}">
                        @error('bkash_account_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bkash Type -->
                    <div class="col-md-6">
                        <label for="bkash_type" class="form-label">Bkash Type</label>
                        <select class="form-select @error('bkash_type') is-invalid @enderror" id="bkash_type" name="bkash_type">
                            <option value="" disabled {{ old('bkash_type', $settings->bkash_type ?? '') == '' ? 'selected' : '' }}>Select Type</option>
                            <option value="personal" {{ old('bkash_type', $settings->bkash_type ?? '') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="agent" {{ old('bkash_type', $settings->bkash_type ?? '') == 'agent' ? 'selected' : '' }}>Agent</option>
                        </select>
                        @error('bkash_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Nagad Account No -->
                    <div class="col-md-6">
                        <label for="nagad_account_no" class="form-label">Nagad Account No</label>
                        <input type="text" class="form-control @error('nagad_account_no') is-invalid @enderror" id="nagad_account_no" name="nagad_account_no" value="{{ old('nagad_account_no', $settings->nagad_account_no ?? '') }}">
                        @error('nagad_account_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nagad Type -->
                    <div class="col-md-6">
                        <label for="nagad_type" class="form-label">Nagad Type</label>
                        <select class="form-select @error('nagad_type') is-invalid @enderror" id="nagad_type" name="nagad_type">
                            <option value="" disabled {{ old('nagad_type', $settings->nagad_type ?? '') == '' ? 'selected' : '' }}>Select Type</option>
                            <option value="personal" {{ old('nagad_type', $settings->nagad_type ?? '') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="agent" {{ old('nagad_type', $settings->nagad_type ?? '') == 'agent' ? 'selected' : '' }}>Agent</option>
                        </select>
                        @error('nagad_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Bank Name -->
                    <div class="col-md-6">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $settings->bank_name ?? '') }}">
                        @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div class="col-md-6">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name" name="account_name" value="{{ old('account_name', $settings->account_name ?? '') }}">
                        @error('account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank Account No -->
                    <div class="col-md-6">
                        <label for="bank_account_no" class="form-label">Bank Account No</label>
                        <input type="text" class="form-control @error('bank_account_no') is-invalid @enderror" id="bank_account_no" name="bank_account_no" value="{{ old('bank_account_no', $settings->bank_account_no ?? '') }}">
                        @error('bank_account_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank Branch -->
                    <div class="col-md-6">
                        <label for="bank_branch" class="form-label">Bank Branch</label>
                        <input type="text" class="form-control @error('bank_branch') is-invalid @enderror" id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $settings->bank_branch ?? '') }}">
                        @error('bank_branch')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
            </form>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection

@section('scripts')
<script>
    // Bootstrap client-side validation example
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })();
</script>
@endsection
