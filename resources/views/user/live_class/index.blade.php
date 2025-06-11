@extends('user.layouts.app')

@section('content')
<div class="app-content content min-vh-100">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Live Classes</li>
                    </ol>
                </nav>
                <h3 class="mb-4 text-primary fw-bold">🎥 Upcoming Live Classes</h3>
            </div>
        </div>

        <div class="content-body">
            <section class="container mt-3">
                @if($liveClasses->count())
                    <div class="row g-4">
                        @foreach($liveClasses as $class)
                            @php
                                $startTime = \Carbon\Carbon::parse($class->start_time);
                                $endTime = \Carbon\Carbon::parse($class->end_time);
                                $now = \Carbon\Carbon::now();
                                $hasEnded = $endTime->isPast();
                                $isLive = $startTime->isPast() && !$hasEnded;
                            @endphp

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card shadow-sm rounded-4 border-0 h-100
                                    {{ $hasEnded ? 'opacity-50' : 'hover-shadow-lg' }}">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-semibold mb-3 text-truncate" title="{{ $class->title }}">
                                            {{ $class->title }}
                                        </h5>

                                        <div class="mb-3 d-flex flex-wrap gap-2">
                                            <span class="badge bg-info d-flex align-items-center" title="Start Time">
                                                <i class="fa fa-play-circle me-1"></i>
                                                {{ $startTime->format('d M, h:i A') }}
                                            </span>
                                            <span class="badge bg-danger d-flex align-items-center" title="End Time">
                                                <i class="fa fa-stop-circle me-1"></i>
                                                {{ $endTime->format('d M, h:i A') }}
                                            </span>
                                        </div>

                                        <div class="mt-auto d-flex gap-2 align-items-center">
                                            @if($hasEnded)
                                                <span class="badge bg-secondary text-white text-center flex-grow-1 py-2 fs-6 rounded-pill">
                                                    ⏰ Class Ended
                                                </span>
                                            @else
                                                <a href="{{ $class->meeting_url }}" target="_blank"
                                                   class="btn btn-primary flex-grow-1 fw-semibold shadow-sm"
                                                   style="border-radius: 50px; font-size: 1rem; letter-spacing: 0.03em;">
                                                    ▶ Join Class
                                                </a>

                                                <button
                                                    class="btn btn-outline-primary copy-btn"
                                                    data-clipboard-text="{{ $class->meeting_url }}"
                                                    title="Copy Meeting URL"
                                                    aria-label="Copy Meeting URL"
                                                    style="border-radius: 50px; padding: 0.45rem 1rem; font-size: 1rem;"
                                                >
                                                    📋 Copy
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info fs-5 text-center py-4 rounded">
                        No live classes found.
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<!-- Clipboard.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clipboard = new ClipboardJS('.copy-btn');

        clipboard.on('success', e => {
            e.trigger.textContent = '✅ Copied!';
            e.trigger.classList.remove('btn-outline-primary');
            e.trigger.classList.add('btn-success');
            setTimeout(() => {
                e.trigger.textContent = '📋 Copy';
                e.trigger.classList.remove('btn-success');
                e.trigger.classList.add('btn-outline-primary');
            }, 2000);
            e.clearSelection();
        });

        clipboard.on('error', () => {
            alert('Copy failed! Please copy manually.');
        });
    });
</script>
@endsection
