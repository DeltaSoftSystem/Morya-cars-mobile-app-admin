<div class="filter-box">

    <div class="filter-head d-flex justify-content-between">
        <h5>Filters</h5>
        <a href="#" id="resetFilter">Reset All</a>
    </div>

    <div class="filter-group">
        <label>Make</label>
        <select id="brand" class="form-select">
            <option value="">All</option>
            @foreach($brands as $brand)
                <option value="{{ $brand }}">{{ $brand }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Body Type</label>
        <select id="body_type" class="form-select">
            <option value="">All</option>
            <option>SUV</option>
            <option>Sedan</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Price</label>
        <div class="d-flex gap-2">
            <input type="number" id="min_price" class="form-control" placeholder="From">
            <input type="number" id="max_price" class="form-control" placeholder="To">
        </div>
    </div>

    <button id="applyFilter" class="btn btn-dark w-100 mt-3">
        Apply Filters
    </button>

</div>
