@extends('admin.layouts.app')

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">

        <div x-data="userSearch()" class="w-2/3 mx-auto py-4" style="width: 40%;">
    <div class="relative">
        <input
            type="text"
            x-model="search"
            placeholder="Search..."
            class="w-full pl-10 pr-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500 transition"
        >

        <button
            type="button"
            class="absolute right-0 top-0 bottom-0 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-r-md transition"
            @click="searchUsers()"
        >
            Search
        </button>
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
                                    <div class="col-auto text-end">
                                        <a href="{{ route('admin_users.create') }}" class="btn btn-primary rounded text-white">
                                            <i class="fa fa-plus"></i> Add User
                                        </a>
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
                                                    <th>Facebook id</th>
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
                                                    <td>{{$user->facebook_id ?? ''}}</td>
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
                                                        <a href="{{route('admin_users.edit',$user->id)}}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin_users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this user?');">
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
                                        {{ $users->appends(request()->query())->links() }}
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

@section('script')
<script>
function userSearch() {
    return {
        search: '',
        searchUsers() {
            if (this.search.trim() !== '') {
                const query = encodeURIComponent(this.search.trim());
                window.location.href = `?search=${query}`;
            }
        }
    }
}
</script>

@endsection
