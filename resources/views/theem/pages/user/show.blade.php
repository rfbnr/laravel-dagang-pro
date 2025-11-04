@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">

                    <!-- Title -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">User Details</h4>
                    </div>

                    <!-- Main Card (Image + Info) -->
                    <div class="card shadow border-0 rounded-4 overflow-hidden mb-4 fade-slide-in">

                        <div class="row g-0 h-100">
                            <!-- Left Side: Image -->
                            {{-- <div class="col-md-4 d-flex align-items-center justify-content-center bg-light p-3"
                                style="border-right: 1px solid #e0e0e046;"> --}}
                            <div class="col-md-4 d-flex align-items-center justify-content-center p-3"
                                style="border-right: 1px solid #dee2e6; background-color: #f0f2f5;">

                                <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                                    class="img-fluid rounded border border-2 border-primary user-image-hover"
                                    style="max-height: 200px; object-fit: contain;">


                            </div>

                            <!-- Right Side: Info -->
                            <div class="col-md-8">
                                <div class="card-body py-4 px-5">
                                    <h4 class="card-title fw-bold text-primary mb-3">{{ $user->name }}</h4>

                                    <ul class="list-group list-group-flush mb-3 small">
                                        <li class="list-group-item px-0"><strong>Email:</strong> {{ $user->email }}</li>

                                        <li class="list-group-item px-0"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}
                                        </li>

                                        <li class="list-group-item px-0"><strong>Role:</strong>
                                            <span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span>
                                        </li>

                                        <li class="list-group-item px-0"><strong>Status:</strong>
                                            @if ($user->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </li>

                                        <li class="list-group-item px-0"><strong>Force Password Change:</strong>
                                            @if ($user->force_password_change)
                                                <span class="badge bg-warning text-dark">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </li>
                                    </ul>

                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Created At: {{ $user->created_at->format('d M Y') }}</span>
                                        <span>Updated At: {{ $user->updated_at->format('d M Y') }}</span>
                                    </div>

                                    <a href="{{ route('user.index') }}" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                        Back to Users
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
