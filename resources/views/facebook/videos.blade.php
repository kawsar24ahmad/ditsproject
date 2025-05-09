@extends('customer.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">📊 Facebook Video Insights</h1>

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
        <div class="container mx-auto px-4 py-6">

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($videoResults as $video)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition">
                    <a href="{{ $video['media'][0]['media_url'] }}" target="_blank">
                        <img src="{{ $video['media'][0]['thumbnail'] }}" alt="Video Thumbnail" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <p class="text-sm text-gray-600 mb-2">{{ \Carbon\Carbon::parse($video['created_time'])->diffForHumans() }}</p>
                        <p class="text-base text-gray-800 mb-3 line-clamp-2">{{ $video['message'] ?: 'No description' }}</p>

                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            <span>👀 Views: {{ $video['total_plays'] }}</span>
                            <span>🔁 Replays: {{ $video['replay_count'] }}</span>
                            <span>⏱ Avg Watch: {{ floor($video['avg_time_watched'] / 1000) }}s</span>
                            <span>📣 Reach: {{ $video['reach'] }}</span>
                            <span>❤️ Likes: {{ $video['likes'] }}</span>
                            <span>💬 Comments: {{ $video['comments'] }}</span>
                            <span>🔄 Shares: {{ $video['shares'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            {{-- Show pagination links --}}

            <div class="mt-4">
                {{ $videoResults->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
