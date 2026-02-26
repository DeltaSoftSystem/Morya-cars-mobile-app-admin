@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-10 mx-auto">

        <div class="card shadow">

            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Accessory Details</h3>

                <a href="{{ route('accessories.index') }}"
                       class="btn btn-secondary btn-sm ml-auto"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <div class="card-body">

                {{-- ================= BASIC INFO ================= --}}
                <h5 class="text-muted">Basic Information</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Name:</strong><br>
                        {{ $accessory->name }}
                    </div>

                    <div class="col-md-4">
                        <strong>Brand:</strong><br>
                        {{ $accessory->brand ?? '—' }}
                    </div>

                    <div class="col-md-4">
                        <strong>Category:</strong><br>
                        {{ $accessory->category->name ?? '—' }}
                    </div>
                </div>

                <div class="mb-4">
                    <strong>Compatibility:</strong><br>
                    {{ $accessory->compatibility ?? '—' }}
                </div>

                <hr>

                {{-- ================= PRICING ================= --}}
                <h5 class="text-muted">Pricing</h5>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Base Price:</strong><br>
                        ₹ {{ number_format($accessory->price, 2) }}
                    </div>

                    <div class="col-md-3">
                        <strong>Discount Type:</strong><br>
                        {{ $accessory->discount_type ?? 'None' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Discount Value:</strong><br>
                        {{ $accessory->discount_value ?? '—' }}
                    </div>

                    <div class="col-md-3">
                        <strong>Final Price:</strong><br>
                        <span class="text-success font-weight-bold">
                            ₹ {{ number_format($accessory->discounted_price, 2) }}
                        </span>
                    </div>
                </div>

                <hr>

                {{-- ================= INVENTORY ================= --}}
                <h5 class="text-muted">Inventory</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>SKU:</strong><br>
                        {{ $accessory->sku ?? '—' }}
                    </div>

                    <div class="col-md-4">
                        <strong>Stock:</strong><br>
                        @if($accessory->stock > 0)
                            <span class="badge badge-success">
                                {{ $accessory->stock }} Available
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <strong>Status:</strong><br>
                        @if($accessory->status)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- ================= POLICY ================= --}}
                <h5 class="text-muted">Return / Replacement Policy</h5>
                <div class="mb-4">

                    @if($accessory->is_replaceable)
                        <span class="badge badge-info mr-2">Replaceable</span>
                    @endif

                    @if($accessory->is_exchangeable)
                        <span class="badge badge-warning mr-2">Exchangeable</span>
                    @endif

                    @if($accessory->is_returnable)
                        <span class="badge badge-success mr-2">Returnable</span>
                    @endif

                    @if(!$accessory->is_replaceable && 
                        !$accessory->is_exchangeable && 
                        !$accessory->is_returnable)
                        <span class="text-muted">No return policy available</span>
                    @endif

                </div>

                <hr>

                {{-- ================= MEDIA ================= --}}
                <h5 class="text-muted">Media</h5>

                <div class="row mb-4">

                    <div class="col-md-4">
                        <strong>Main Image:</strong><br>
                        @if($accessory->image)
                            <img src="{{ asset('storage/'.$accessory->image) }}"
                                 class="img-fluid rounded border mt-2"
                                 style="max-height:150px;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <strong>Gallery:</strong><br>

                        <div class="row mt-2">
                            @if($accessory->gallery)
                                @foreach(json_decode($accessory->gallery) as $img)
                                    <div class="col-md-3 mb-2">
                                        <img src="{{ asset('storage/'.$img) }}"
                                             class="img-fluid rounded border">
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <span class="text-muted">No Gallery Images</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <hr>

                {{-- ================= DESCRIPTION ================= --}}
                <h5 class="text-muted">Description</h5>
                <div class="mb-2">
                    {!! nl2br(e($accessory->description)) !!}
                </div>

            </div>
        </div>

    </div>
</div>

@endsection