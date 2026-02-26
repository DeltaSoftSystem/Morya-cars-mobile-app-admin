@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i> User Details
                    </h4>
                </div>

                <div class="card-body">

                    {{-- Basic Info --}}
                    <h6 class="text-uppercase text-muted mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <strong>Name</strong><br>
                            {{ $user->name }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Email</strong><br>
                            {{ $user->email }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Mobile</strong><br>
                            {{ $user->mobile }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Role</strong><br>
                            <span class="badge bg-primary">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    {{-- Verification Status --}}
                    <h6 class="text-uppercase text-muted mb-3">Verification Status</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <strong>Mobile Verified</strong><br>
                            <span class="badge {{ $user->is_mobile_verified ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_mobile_verified ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Email Verified</strong><br>
                            <span class="badge {{ $user->is_email_verified ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_email_verified ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    {{-- Account Activity --}}
                    <h6 class="text-uppercase text-muted mb-3">Account Activity</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <strong>Status</strong><br>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Last Login</strong><br>
                            {{ $user->last_login_at
                                ? $user->last_login_at->format('d M Y, H:i')
                                : 'Never Logged In' }}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Last Login IP</strong><br>
                            {{ $user->last_login_ip ?? 'N/A' }}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Account Created</strong><br>
                            {{ $user->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                </div>

                <div class="card-footer text-end bg-light">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
