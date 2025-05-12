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
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active"><a href="{{route('admin.media.index')}}">Media</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Media Table -->
            <section id="media-list">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col">
                                        <h4 class="card-title text-bold text-lg mb-0">Media List</h4>
                                    </div>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('admin.media.create') }}" class="btn btn-primary rounded text-white">
                                            <i class="fa fa-plus"></i> Add Media
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">

<h4 class="mb-2">🎬 YouTube Videos</h4>
@forelse($media->where('type', 'youtube') as $item)
    <div class="mb-3 p-3 border rounded position-relative">
        <h5>{{ $item->title }}</h5>

        @php
            // Use the dynamic URL from the $item's youtube_link
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

        <div class="mt-2">
            <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-primary">✏️ Edit</a>

            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
            </form>
        </div>
    </div>
@empty
    <p class="text-muted">কোনো ইউটিউব ভিডিও পাওয়া যায়নি।</p>
@endforelse


<!-- Audio Files -->
<h4 class="mt-4 mb-2">🎧 Audio Files</h4>
@forelse($media->where('type', 'audio') as $item)
    <div class="mb-3 p-3 border rounded">
        <h5>{{ $item->title }}</h5>
        <audio controls>
            <source src="{{ asset($item->audio_file) }}" type="audio/mpeg">
            আপনার ব্রাউজার অডিও প্লে করতে পারে না।
        </audio>
        <br>
        <a href="{{ asset($item->audio_file) }}" class="btn btn-sm btn-success mt-1" download>
            ⬇️ অডিও ডাউনলোড করুন
        </a>
        <div class="mt-2">
            <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-primary">✏️ Edit</a>

            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
            </form>
        </div>
    </div>
@empty
    <p class="text-muted">কোনো অডিও ফাইল পাওয়া যায়নি।</p>
@endforelse


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Media Table -->
        </div>
    </div>
</div>

@stop
