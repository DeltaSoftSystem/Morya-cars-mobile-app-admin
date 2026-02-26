@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Service Request #{{ $request->id }}</h5>
        <a href="{{ route('service-requests.index') }}"
           class="btn btn-sm btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- LEFT: DETAILS -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body p-3">

                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Service</div>
                        <div class="col-md-9 fw-semibold">
                            {{ $request->service->name }}
                        </div>
                    </div>
                    @if($request->item)
<div class="row mb-2">
    <div class="col-md-3 text-muted">Service Item</div>
    <div class="col-md-9">
        {{ $request->item->name }}

        @if($request->item->price)
            <span class="text-muted">
                (₹{{ number_format($request->item->price, 2) }})
            </span>
        @endif
    </div>
</div>
@endif

                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">User</div>
                        <div class="col-md-9">
                            {{ $request->name }} <br>
                            <small class="text-muted">{{ $request->mobile }}</small>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">City</div>
                        <div class="col-md-9">{{ $request->city }} - {{ $request->pincode }}</div>
                    </div>
                    
                    <div class="row mb-2">
    <div class="col-md-3 text-muted">Car</div>
    <div class="col-md-9">
        @if($request->car)
            {{ $request->car->make }} {{ $request->car->model }}
            <br>
            <small class="text-muted">
                Reg No: {{ $request->car->registration_number ?? '-' }}
            </small>
        @else
            -
        @endif
    </div>
</div>


                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Preferred</div>
                        <div class="col-md-9">
                            {{ $request->preferred_date ?? '-' }}
                            {{ $request->preferred_time ? ' | '.$request->preferred_time : '' }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 text-muted">Description</div>
                        <div class="col-md-9">
                            {{ $request->description ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT: STATUS UPDATE -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-3">

                    <form method="POST"
                          action="{{ route('service-requests.update-status', $request->id) }}">
                        @csrf

                        <div class="mb-2">
                            <label class="form-label mb-1">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                @foreach(['pending','assigned','in_progress','completed','cancelled'] as $status)
                                    <option value="{{ $status }}"
                                        {{ $request->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Admin Comment</label>
                            <textarea name="admin_comment"
                                      class="form-control form-control-sm"
                                      rows="3">{{ $request->admin_comment }}</textarea>
                        </div>

                        <button class="btn btn-primary btn-sm w-100">
                            Update
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
