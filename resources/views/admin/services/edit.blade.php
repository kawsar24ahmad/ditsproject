@extends('admin.layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .form-field-row {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .form-field-row input,
    .form-field-row select {
        margin-bottom: 10px;
    }

    .remove-field {
        margin-top: 10px;
    }

    .add-field-btn-container {
        text-align: right;
        margin-top: 20px;
    }

    .form-group label {
        font-weight: 600;
    }

    .form-control,
    .btn {
        border-radius: 5px;
    }
</style>
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                                <li class="breadcrumb-item active">Edit Service</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            {{-- Service Edit Form --}}
            <section class="basic-vertical-layouts">
                <form class="form form-vertical" action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <div class="card-header">
                                    <h4 class="card-title">Edit Service</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="row">
                                            {{-- Title --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" value="{{ old('title', $service->title) }}" required>
                                                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Price --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Price</label>
                                                    <input type="number" name="price" class="form-control" value="{{ old('price', $service->price) }}">
                                                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Offer Price --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Offer Price</label>
                                                    <input type="number" name="offer_price" class="form-control" value="{{ old('offer_price', $service->offer_price) }}">
                                                    @error('offer_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Category --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control select2" name="category_id">
                                                        <option value="">-- Select Category --</option>
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Thumbnail --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Thumbnail</label>
                                                    <input type="file" name="thumbnail" class="form-control">
                                                    @if($service->thumbnail)
                                                    <img src="{{ asset($service->thumbnail) }}" alt="Thumbnail" height="150" width="150" class="mt-1">
                                                    @endif
                                                    @error('thumbnail') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Icon --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Icon</label>
                                                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="Enter icon">
                                                    @error('icon') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control summernote">{{ old('description', $service->description) }}</textarea>
                                                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>

                                            {{-- Service Type --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Type</label>
                                                    <input type="text" name="type" class="form-control" value="{{ old('type', $service->type) }}">
                                                </div>
                                            </div>

                                            {{-- Submit --}}
                                            <div class="col-12 text-end">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Task Section --}}
            {{-- Tasks Section --}}
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Service Tasks for: <span class="text-primary">{{ $service->title }}</span></h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.store', $service->id) }}" method="POST" class="d-flex align-items-center mb-3 gap-2">
                        @csrf
                        <input type="text" name="title" class="form-control" placeholder="Add new task..." required>
                        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add</button>
                    </form>

                    @if($service->tasks->count())
                    <ul class="list-group">
                        @foreach($service->tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $task->title }}</span>
                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="mb-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted">No tasks found for this service.</p>
                    @endif
                </div>
            </div>

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
        $('.select2').select2();
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
@endsection
