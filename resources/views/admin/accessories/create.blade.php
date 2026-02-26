@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title mb-0">Add Accessory</h3>
            </div>

            <form action="{{ route('accessories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">

    {{-- ================= BASIC DETAILS ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Basic Details</strong>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <label>Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Accessory Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Brand</label>
                    <input type="text" name="brand" class="form-control">
                </div>
            </div>

            <div class="mt-3">
                <label>Compatibility</label>
                <input type="text" name="compatibility"
                       class="form-control"
                       placeholder="BMW 3 Series, Audi A4">
            </div>

        </div>
    </div>


    {{-- ================= PRICING ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Pricing</strong>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-3">
                    <label>Base Price *</label>
                    <input type="number" id="price" name="price"
                           class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Discount Type</label>
                    <select name="discount_type" id="discount_type"
                            class="form-control">
                        <option value="">None</option>
                        <option value="flat">Flat</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Discount Value</label>
                    <input type="number" id="discount_value"
                           name="discount_value"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Final Price</label>
                    <input type="number" id="discounted_price"
                           name="discounted_price"
                           class="form-control bg-light" readonly>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= INVENTORY ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Inventory</strong>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Stock Quantity *</label>
                    <input type="number" name="stock"
                           class="form-control" value="0" required>
                </div>

                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= POLICY ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Return / Replacement Policy</strong>
        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox"
                               name="is_replaceable"
                               value="1"
                               class="custom-control-input"
                               id="replaceable">
                        <label class="custom-control-label"
                               for="replaceable">
                            Replaceable
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox"
                               name="is_exchangeable"
                               value="1"
                               class="custom-control-input"
                               id="exchangeable">
                        <label class="custom-control-label"
                               for="exchangeable">
                            Exchangeable
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox"
                               name="is_returnable"
                               value="1"
                               class="custom-control-input"
                               id="returnable">
                        <label class="custom-control-label"
                               for="returnable">
                            Returnable
                        </label>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- ================= MEDIA ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Media</strong>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <label>Main Image</label>
                    <input type="file"
                           name="image"
                           class="form-control-file">
                </div>

                <div class="col-md-6">
                    <label>Gallery Images</label>
                    <input type="file"
                           name="gallery[]"
                           class="form-control-file"
                           multiple>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= DESCRIPTION ================= --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Description</strong>
        </div>
        <div class="card-body">
            <textarea name="description"
                      class="form-control"
                      rows="4"
                      placeholder="Enter product description..."></textarea>
        </div>
    </div>

</div>


                <div class="card-footer text-right">
                    <a href="{{ route('accessories.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button class="btn btn-success">
                        Save Accessory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Discount Auto Calculation --}}
<script>
document.addEventListener('input', function () {
    let price = parseFloat(document.getElementById('price').value) || 0;
    let discount = parseFloat(document.getElementById('discount_value').value) || 0;
    let type = document.getElementById('discount_type').value;

    let finalPrice = price;

    if (type === 'percentage') {
        finalPrice = price - (price * discount / 100);
    } else if (type === 'flat') {
        finalPrice = price - discount;
    }

    document.getElementById('discounted_price').value =
        finalPrice > 0 ? finalPrice.toFixed(2) : price.toFixed(2);
});
</script>
@endsection
