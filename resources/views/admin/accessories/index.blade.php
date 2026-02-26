@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Accessories</h3>

                <a href="{{ route('accessories.create') }}"
                   class="btn btn-sm btn-success ml-auto">
                    <i class="fas fa-plus"></i> Add Accessory
                </a>
            </div>

            <div class="card-body">

                <!-- Live Search -->
                <input type="text" id="search" class="form-control mb-3"
                       placeholder="Search accessory name, brand or category"
                       value="{{ $search ?? '' }}">

                <!-- Table Wrapper -->
                <div id="accessoryTable">
                    @include('admin.accessories.table', ['accessories' => $accessories])
                </div>

            </div>
        </div>
    </div>
</div>

{{-- AJAX Search + Pagination --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('search');
    const wrapper = document.getElementById('accessoryTable');

    function loadTable(url) {
        fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(res => res.json())
            .then(data => {
                wrapper.innerHTML = data.html;
                attachPaginationEvents();
            });
    }

    // Live search
    search.addEventListener('keyup', function () {
        const q = encodeURIComponent(this.value.trim());
        loadTable("{{ route('accessories.index') }}?search=" + q);
    });

    // Pagination
    function attachPaginationEvents() {
        wrapper.querySelectorAll('#paginationLinks a').forEach(a => {
            a.addEventListener('click', function (e) {
                e.preventDefault();

                let url = this.href;
                if (search.value.trim()) {
                    url += "&search=" + encodeURIComponent(search.value);
                }
                loadTable(url);
            });
        });
    }

    attachPaginationEvents();
});
</script>
@endsection
