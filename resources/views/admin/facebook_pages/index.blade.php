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
                                <li class="breadcrumb-item active">Facebook Pages</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="horizontal-vertical">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col">
                                        <h4 class="card-title text-bold text-lg mb-0">All Connected Facebook Pages</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    <th>Access Token</th>
                                                    <th>Page Name</th>
                                                    <th>Category</th>
                                                    <th>Profile</th>
                                                    <th>Cover</th>
                                                    <th>Status</th>
                                                    <th>Username</th>
                                                    <th>Likes</th>
                                                    <th>Followers</th>
                                                    <th>Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($facebookPages as $key => $page)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $page->user->name ?? 'N/A' }}<br><small>{{ $page->user->email ?? '' }}</small></td>
                                                    <td>{{ Str::limit($page->page_access_token, 30) }}</td>
                                                    <td><a href="https://facebook.com/{{ $page->page_id }}" target="_blank">{{ $page->page_name }}</a></td>
                                                    <td>{{ $page->category ?? 'N/A' }}</td>
                                                    <td><img src="{{ $page->profile_picture }}" alt="Profile" height="30"></td>
                                                    <td><img src="{{ $page->cover_photo }}" alt="Cover" height="30"></td>
                                                    <td><span class="badge badge-{{ $page->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($page->status) }}</span></td>
                                                    <td>{{ $page->page_username ?? 'N/A' }}</td>
                                                    <td>{{ $page->likes ?? 0 }}</td>
                                                    <td>{{ $page->followers ?? 0 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($page->created_at)->format('d M, Y') }}</td>
                                                    <td>
    @if($page->status === 'active')
        <form action="{{ route('admin.facebook-pages.toggleStatus', $page->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-warning">Deactivate</button>
        </form>
    @else
        <form action="{{ route('admin.facebook-pages.toggleStatus', $page->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-success">Activate</button>
        </form>
    @endif
</td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="mt-2">
                                            {{ $facebookPages->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
