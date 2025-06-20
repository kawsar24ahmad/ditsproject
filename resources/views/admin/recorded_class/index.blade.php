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
                                <li class="breadcrumb-item active">Recorded Classes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section class="container mt-2">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">🎥 রেকর্ডেড ক্লাস লিস্ট</h4>
                        <a href="{{ route('admin.createRecordedClass') }}" class="btn btn-blue btn-sm">
                            <i class="fa fa-plus"></i> নতুন যুক্ত করুন
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($classes->count())
                            <div class="row">
                                @foreach ($classes as $class)
                                    @php
                                        $videoId = App\Helpers\YouTubeHelper::extractYoutubeId($class->youtube_link);
                                    @endphp

                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card border shadow-sm h-100">
                                            @if ($videoId)
                                                {{-- YouTube Video --}}
                                                <div class="ratio ratio-16x9">
                                                    <iframe
                                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                                        title="{{ $class->title }}"
                                                        allowfullscreen>
                                                    </iframe>
                                                </div>
                                            @elseif($class->video_path)
                                                {{-- MP4 Video --}}
                                                <div class="ratio ratio-16x9">
                                                    <video controls>
                                                        <source src="{{ asset($class->video_path) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @else
                                                <div class="p-3 text-center text-muted">
                                                    No video available.
                                                </div>
                                            @endif

                                            <div class="card-body">
                                                <div>
                                                    <h5 class="card-title mb-1">{{ $class->title }}</h5>
                                                </div>
                                            </div>

                                            <div class="card-footer text-end">
                                                <!-- <a href="{{ route('admin.editRecordedClass', $class->id) }}" class="btn btn-sm btn-outline-primary">
                                                    ✏️ Edit
                                                </a> -->

                                                <form action="{{ route('admin.recorded-class.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        🗑️ Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">🚫 কোনো রেকর্ডেড ক্লাস পাওয়া যায়নি।</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
