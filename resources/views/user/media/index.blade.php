@extends('user.layouts.app')

@section('css')

<style>
    audio::-webkit-media-controls-panel {
        background-color: #f1f1f1;
    }

    audio {
        border-radius: 8px;
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
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb bg-light p-2 rounded">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Support</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="media-list">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="text-center fw-bold text-success my-5 fs-4">
                                <h2>নতুনদের জন্য কনটেন্ট ক্রিয়েটর হওয়ার A to Z গাইড</h2>
                            </div>
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Support Media</h4>
                            </div>

                            <div class="card-body">
                                <!-- YouTube Videos -->
                                @if ($media->where('type', 'youtube')->count())
                                 <h5 class="mb-3 mt-2 text-dark"><i class="bi bi-youtube me-1 text-danger"></i> YouTube Videos</h5>
                                <div class="row">
                                    @forelse($media->where('type', 'youtube') as $item)
                                        <div class="col-lg-6 col-md-12 mb-4">
                                            <div class="border rounded shadow-sm p-3 bg-white h-100">

@php


    $videoId = App\Helpers\YouTubeHelper::extractYoutubeId($item->youtube_link);
@endphp

@if ($videoId)
    <div class="ratio ratio-16x9">
        <iframe
            src="https://www.youtube.com/embed/{{ $videoId }}"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    </div>
@endif
                                                <h6 class="my-2">{{ $item->title }}</h6>

                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted">কোনো ইউটিউব ভিডিও পাওয়া যায়নি।</div>
                                    @endforelse
                                </div>
                                @endif

                                <!-- Audio Files -->
                           @if ($media->where('type', 'audio')->count())
    <h5 class="mb-3 mt-4 text-success text-center fs-3 fw-bold">
        <i class="bi bi-music-note-beamed me-1 text-info"></i> কপিরাইট ফ্রি মিউজিক ডাউনলোড করুন
    </h5>

    <div class="row">
        @foreach ($media->where('type', 'audio') as $index => $item)
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 text-primary fs-4 fw-bold">{{ $loop->iteration }}.</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">অডিও {{ $loop->iteration }}</h6>
                                <p class="text-muted small mb-0">ডাউনলোড করুন অথবা শুনুন</p>
                            </div>
                        </div>

                        <audio controls class="w-100 rounded mb-3" style="background-color: #f8f9fa;">
                            <source src="{{ asset($item->audio_file) }}" type="audio/mpeg">
                            আপনার ব্রাউজার অডিও প্লে করতে পারে না।
                        </audio>

                        <a href="{{ asset($item->audio_file) }}" class="btn btn-info text-white w-100" download>
                            <i class="bi bi-download me-1"></i> ডাউনলোড করুন
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif


                            </div> <!-- card-body -->
                        </div> <!-- card -->
                    </div> <!-- col -->
                </div> <!-- row -->
            </section>
        </div>
    </div>
</div>

@stop
