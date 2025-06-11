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
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Live Classes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section class="container mt-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">🎥 Live Classes</h4>
                        <a href="{{ route('live_class.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Add New Class
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($liveClasses->count())
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Meeting URL</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($liveClasses as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->title }}</td>
                                            <td>
                                                <a href="{{ $class->meeting_url }}" target="_blank">
                                                    Join Link 🔗
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">Start: {{ \Carbon\Carbon::parse($class->start_time)->format('d M, h:i A') }}</span><br>
                                                <span class="badge bg-danger">End: {{ \Carbon\Carbon::parse($class->end_time)->format('d M, h:i A') }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('live_class.edit', $class->id) }}" class="btn btn-sm btn-warning">
                                                    ✏️ Edit
                                                </a>

                                                <form action="{{ route('live_class.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">No live classes found.</div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
