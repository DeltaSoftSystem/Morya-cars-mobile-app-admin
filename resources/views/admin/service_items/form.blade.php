<div class="card">
    <div class="card-body p-3">

        <!-- Service (readonly) -->
        <div class="mb-2">
            <label class="form-label mb-1">Service</label>
            <input type="text"
                   class="form-control form-control"
                   value="{{ $service->name }}"
                   disabled>
        </div>

        <!-- Item Name -->
        <div class="mb-2">
            <label class="form-label mb-1">Item Name</label>
            <input type="text"
                   name="name"
                   class="form-control form-control"
                   value="{{ old('name', $item->name ?? '') }}"
                   required>
        </div>

        <!-- Price + Status (same row) -->
        <div class="row mb-2">
            <div class="col-md-6">
                <label class="form-label mb-1">Price</label>
                <input type="number"
                       name="price"
                       step="0.01"
                       class="form-control form-control"
                       value="{{ old('price', $item->price ?? '') }}"
                       placeholder="Optional">
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1">Status</label>
                <select name="is_active" class="form-control form-control">
                    <option value="1" {{ old('is_active', $item->is_active ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ old('is_active', $item->is_active ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="form-label mb-1">Description</label>
            <textarea name="description"
                      rows="2"
                      class="form-control form-control"
                      placeholder="Optional">{{ old('description', $item->description ?? '') }}</textarea>
        </div>

    </div>
</div>
