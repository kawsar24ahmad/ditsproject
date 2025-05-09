@extends('customer.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">📊 Facebook Post Insights</h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Insights</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @forelse($results as $index => $post)
                <div class="col-md-6 mb-4">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between">
                            <span><i class="fas fa-feather-alt me-1"></i> Post #{{ $index + 1 }}</span>
                            <small>{{ \Carbon\Carbon::parse($post['created_time'])->format('M d, Y h:i A') }}</small>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">{{ $post['message'] ?? '' }}</p>

                            <hr class="my-2">
                            @if(!empty($post['media']))
                            <div class="media-gallery" style="display:flex; flex-wrap:wrap; gap:10px;">
                                @foreach($post['media'] as $media)
                                @if($media['type'] === 'photo' || empty($media['type']))
                                <div class="card" style="width: 200px;">
                                    <a href="{{ $media['link'] }}" target="_blank">
                                        <img src="{{ $media['media_url'] }}" alt="Post Media" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    </a>

                                    {{-- Optional: If each media item has individual stats --}}
                                    @if(isset($media['likes']) || isset($media['comments']) || isset($media['shares']))
                                    <div class="card-body p-2">
                                        <div class="text-center">
                                            <small><i class="fas fa-thumbs-up text-success"></i> {{ $media['likes'] ?? 0 }}</small> |
                                            <small><i class="fas fa-comments text-info"></i> {{ $media['comments'] ?? 0 }}</small> |
                                            <small><i class="fas fa-share text-warning"></i> {{ $media['shares'] ?? 0 }}</small>
                                        </div>
                                        <div class="text-center mt-1">
                                            <small><i class="fas fa-eye text-secondary"></i> {{ $media['reach'] ?? 0 }}</small> |
                                            <small><i class="fas fa-user-check text-dark"></i> {{ $media['engagement'] ?? 0 }}</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif



                            <div class="row text-center">
                                <div class="col">
                                    <span class="badge bg-success mb-1"><i class="fas fa-thumbs-up"></i> Likes</span>
                                    <h5>{{ $post['likes'] }}</h5>
                                </div>
                                <div class="col">
                                    <span class="badge bg-info mb-1"><i class="fas fa-comments"></i> Comments</span>
                                    <h5>{{ $post['comments'] }}</h5>
                                </div>
                                <div class="col">
                                    <span class="badge bg-warning mb-1"><i class="fas fa-share"></i> Shares</span>
                                    <h5>{{ $post['shares'] }}</h5>
                                </div>
                                <div class="col">
                                    <span class="badge bg-secondary mb-1"><i class="fas fa-eye"></i> Reach</span>
                                    <h5>{{ $post['reach'] ?? 0 }}</h5>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        No Facebook post data found.
                    </div>
                </div>
                @endforelse
            {{-- Show pagination links --}}

                <div class="mt-4">
                    {{ $results->links() }}
                </div>
            </div>

        </div>
    </section>
</div>
@stop
