@extends('customer.layouts.app')

@section('css')
<style>
    .cover-photo {
        height: 160px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .page-info {
        position: absolute;
        top: 150px;
        left: 1rem;
        display: flex;
        align-items: center;
        z-index: 2;
    }

    .profile-picture {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid #fff;
        margin-right: 1rem;
        object-fit: cover;
        background: white;
    }

    .card h5,
    .card .small {
        margin: 0;
    }
</style>



@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0 text-dark">Service Dashboard</h1>
                    </div>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Main row -->
            <div class="row">


                @php
                $facebookPages = auth()->user()->facebookPages->where('status', 'active');
                @endphp
                <div class="d-flex text-2xl mb-3 justify-content-center">
                    <h1 class="m-0 text-dark">Your Pages</h1>
                </div>

                @foreach ($facebookPages as $page)
                <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow border-0 rounded-3 overflow-hidden position-relative">

                            {{-- Cover Photo --}}
                            @if ($page->cover_photo)
                            <div class="cover-photo" style="background-image: url('{{ $page->cover_photo }}');"></div>
                            @else
                            <div class="cover-photo bg-secondary"></div>
                            @endif

                            {{-- Profile Picture & Page Info --}}
                            <div class="page-info px-3">
                                <img src="{{ $page->profile_picture }}" alt="Profile"
                                    class="profile-picture shadow-sm">
                                <div class="mt-2">
                                    <h5 class="mb-1 text-black">{{ $page->page_name }}</h5>
                                    @if ($page->category)
                                    <div class="text-black-50 small">{{ $page->category }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body for Stats --}}
                            <div class="card-body mt-5 pt-4">
                                <div>
                                    @if ($page->likes)
                                    <span class="badge bg-primary me-1">👍 {{ number_format($page->likes) }} Likes</span>
                                    @endif
                                    @if ($page->followers)
                                    <span class="badge bg-info">👥 {{ number_format($page->followers) }} Followers</span>
                                    @endif
                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <span class="badge bg-success">{{ ucfirst($page->status) }}</span>
                                    <div class="gap-1 d-flex">
                                        <a class="badge badge-primary" href="{{ route('facebook.posts', $page->id) }}">Posts</a>
                                        <a class="badge badge-danger" href="{{ route('facebook.videos', $page->id) }}">Videos</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                @endforeach
            </div>





            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
