@php
    $offer = $offer ?? null;
@endphp

<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text"
           name="title"
           class="form-control"
           value="{{ old('title', $offer->title ?? '') }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description"
              class="form-control"
              rows="3">{{ old('description', $offer->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_type" class="form-control" required>
            <option value="percentage"
                {{ old('discount_type', $offer->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>
                Percentage
            </option>
            <option value="fixed"
                {{ old('discount_type', $offer->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>
                Fixed
            </option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number"
               name="discount_value"
               class="form-control"
               value="{{ old('discount_value', $offer->discount_value ?? '') }}"
               min="1"
               required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Applies To</label>
        <select name="applies_to" class="form-control" required>
            <option value="accessories"
                {{ old('applies_to', $offer->applies_to ?? '') == 'accessories' ? 'selected' : '' }}>
                Accessories
            </option>
            <option value="workshop"
                {{ old('applies_to', $offer->applies_to ?? '') == 'workshop' ? 'selected' : '' }}>
                Workshop
            </option>
            <option value="car_listing"
                {{ old('applies_to', $offer->applies_to ?? '') == 'car_listing' ? 'selected' : '' }}>
                Car Listing
            </option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Start Date</label>
        <input type="date"
               name="start_date"
               class="form-control"
               value="{{ old('start_date', $offer->start_date ?? '') }}"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">End Date</label>
        <input type="date"
               name="end_date"
               class="form-control"
               value="{{ old('end_date', $offer->end_date ?? '') }}"
               required>
    </div>
</div>
