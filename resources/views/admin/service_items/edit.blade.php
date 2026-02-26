@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h5>Edit Service Item – {{ $service->name }}</h5>
        <a href="{{ route('services.items.index', $service->id) }}"
           class="btn btn-sm btn-secondary">← Back</a>
    </div>

    <div class="row">
        <div class="col-md-7 m-auto">
    <form method="POST"
          action="{{ route('services.items.update', [$service->id, $item->id]) }}">
        @csrf
        @method('PUT')

        @include('admin.service_items.form', ['item' => $item])

        <div class="mt-3 text-center">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('services.items.index', $service->id) }}"
               class="btn btn-light">Cancel</a>
        </div>
    </form>
        </div></div>
</div>
@endsection
