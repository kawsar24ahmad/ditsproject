@extends('employee.layouts.app')

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        {{-- <h2 class="content-header-title float-left mb-0">Brand</h2> --}}
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('employee.dashboard')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{route('employeeUsers')}}">users</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Scroll - horizontal and vertical table -->
            <section id="horizontal-vertical">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col">
                                        <h4 class="card-title text-bold text-lg mb-0">User List</h4>
                                    </div>

                                </div>
                            </div>




                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Starting Followers</th>
                                                    <th>Facebook id</th>
                                                    <th>Page link</th>
                                                    <th>Role</th>
                                                    <th>Image</th>
                                                    <th>Status</th>
                                                    <th>Added By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->email}}</td>
                                                    <td>{{$user->phone}}</td>
                                                    <td>{{$user->starting_followers}}</td>
                                                    <td>{{$user->fb_id_link ?? ''}}</td>
                                                    <td>{{$user->fb_page_link ?? ''}}</td>
                                                    <td>{{$user->role}}</td>
                                                    <td>
                                                        @if ($user->avatar)
                                                        @if (file_exists($user->avatar))
                                                        <img src="{{asset($user->avatar)}}" width="80px">
                                                    </td>
                                                    @else
                                                    <img src="{{$user->avatar}}" width="80px"></td>
                                                    @endif
                                                    @else
                                                    <img src="{{asset('default.png')}}" width="80px"></td>
                                                    @endif
                                                    <td>
                                                        @if($user->status == "active")
                                                        <span class="badge bg-success">Active</span>
                                                        @else
                                                        <span class="badge bg-warning">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->added_by)
                                                        {{ $user->addedBy->name ?? 'N/A' }}
                                                        @else
                                                        N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('employeeUsers.edit',$user->id)}}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('employeeUsers.delete', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Scroll - horizontal and vertical table -->
        </div>
    </div>
</div>

@stop
