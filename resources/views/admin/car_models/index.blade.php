@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Car Models</h3>

        <a href="{{ route('car-models.create') }}"
        class="btn btn-sm btn-success ml-auto">
            <i class="fas fa-plus"></i> Add Models
        </a>
    </div>

    <div class="card-body">

        <!-- Live Search Input -->
        <input type="text" id="search" class="form-control mb-3"
               placeholder="Search model name or make"
               value="{{ $search ?? '' }}"> 

        <!-- Table Wrapper -->
        <div id="modelTable">
            @include('admin.car_models.table', ['models' => $models])
        </div>

    </div>
</div>
    </div>
</div>


<!-- AJAX Search + Pagination -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('search');
    const wrapper = document.getElementById('modelTable');

    function loadTable(url) {
        fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(res => res.json())
            .then(data => {
                wrapper.innerHTML = data.html;
                attachPaginationEvents();
            });
    }

    // Search on keyup
    search.addEventListener('keyup', function () {
        const q = encodeURIComponent(this.value.trim());
        loadTable("{{ route('car-models.index') }}?search=" + q);
    });

    // AJAX Paginate
    function attachPaginationEvents() {
        wrapper.querySelectorAll('#paginationLinks a').forEach(a => {
            a.addEventListener('click', function(e) {
                e.preventDefault();

                let url = this.href;

                if (search.value.trim()) {
                    url += "&search=" + encodeURIComponent(search.value);
                }

                loadTable(url);
            });
        });
    }

    attachPaginationEvents(); // initial
});
</script>
@endsection
