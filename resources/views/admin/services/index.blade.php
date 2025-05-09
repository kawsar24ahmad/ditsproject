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
                                <li class="breadcrumb-item active"><a href="{{ route('admin.services.index') }}">Services</a></li>
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
                                        <h4 class="card-title text-bold text-lg mb-0">Service List</h4>
                                    </div>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('admin.services.create') }}" class="btn btn-primary rounded text-white">
                                            <i class="fa fa-plus"></i> Add Service
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
                                                    <th>SL</th>
                                                    <th>Title</th>
                                                    <th>Price</th>
                                                    <th>Offer Price</th>
                                                    <th>Category</th>
                                                    <th>Thumbnail</th>
                                                    <th>Icon</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $service->title }}</td>
                                                    <td>{{ $service->price }}</td>
                                                    <td>{{ $service->offer_price }}</td>
                                                    <td>{{ $service->category?->title ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($service->thumbnail)
                                                            <img width="100" src="{{ asset( $service->thumbnail) }}" height="100" alt="thumb">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->icon ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="text-primary me-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this service?');">
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
                                        {{ $services->appends(request()->query())->links() }}
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

@stop
