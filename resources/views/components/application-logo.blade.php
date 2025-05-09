@php
$settings = App\Models\SiteSetting::first();
@endphp
@if (isset($settings->favicon) && file_exists($settings->favicon))
    <img width="60" src="{{ asset($settings->favicon) }}" alt="">
@endif
