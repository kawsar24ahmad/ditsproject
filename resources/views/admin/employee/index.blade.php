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
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active"><a href="{{route('admin.employee.index')}}">Employee</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Employee Table -->
            <section id="employee-table">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow rounded-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 fw-bold">Employee List</h4>
                                <a href="{{ route('admin_users.create') }}" class="btn btn-sm btn-primary rounded-pill">
                                    <i class="fa fa-plus me-1"></i> Add User
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Total Tasks</th>
                                                <th>Pending</th>
                                                <th>In Progress</th>
                                                <th>Completed</th>
                                                <th>Today worked</th>
                                                <th>Role</th>
                                                <th>Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <!-- Task Counts -->
                                                <td>
                                                    <a href="{{ route('assignments.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-primary">
                                                        {{ $user->assignments->count() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('assignments.index', ['user_id' => $user->id, 'status' => 'pending']) }}" class="btn btn-sm btn-warning">
                                                        {{ $user->assignments->where('status', 'pending')->count() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('assignments.index', ['user_id' => $user->id, 'status' => 'in_progress']) }}" class="btn btn-sm btn-info">
                                                        {{ $user->assignments->where('status', 'in_progress')->count() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('assignments.index', ['user_id' => $user->id, 'status' => 'completed']) }}" class="btn btn-sm btn-success">
                                                        {{ $user->assignments->where('status', 'completed')->count() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('assignments.index', ['user_id' => $user->id, 'today_only' => 1]) }}" class="btn btn-sm btn-danger">
                                                        {{ $user->assignments->where('updated_at', '>=', now()->startOfDay())->count() }}
                                                    </a>
                                                </td>

                                                <!-- Role -->
                                                <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>

                                                <!-- Image -->
                                                <td>
                                                    <img src="{{ file_exists($user->avatar) ? asset($user->avatar) : ($user->avatar ?: asset('default.png')) }}" alt="Avatar" class="rounded-circle" width="50" height="50">
                                                </td>



                                                <!-- Actions -->
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin_users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin_users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ $users->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /Employee Table -->
        </div>
    </div>
</div>
@endsection
