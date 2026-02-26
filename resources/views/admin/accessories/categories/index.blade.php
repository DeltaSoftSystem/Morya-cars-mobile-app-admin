@extends('layouts.app')

@section('content')
<div class="row">

    {{-- ADD / EDIT CATEGORY --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" id="formTitle">Add Category</h5>
            </div>

            <form id="categoryForm" method="POST"
                  action="{{ route('accessory-categories.store') }}">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">
                <input type="hidden" id="category_id">

                <div class="card-body">
                    <div class="form-group">
                        <label>Category Name *</label>
                        <input type="text" name="name" id="name"
                               class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button class="btn btn-success" id="submitBtn">Save</button>
                    <button type="button" class="btn btn-secondary d-none"
                            id="cancelEdit">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CATEGORY LIST --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Categories</h5>

                <input type="text" id="search"
                    class="form-control form-control-sm ml-auto"
                    style="max-width: 220px;"
                    placeholder="Search category...">
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTable">
                        @include('admin.accessories.categories.table', ['categories' => $categories])
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

{{-- Inline Edit Script --}}
<script>
function editCategory(category) {
    document.getElementById('formTitle').innerText = 'Edit Category';
    document.getElementById('submitBtn').innerText = 'Update';
    document.getElementById('cancelEdit').classList.remove('d-none');

    document.getElementById('name').value = category.name;
    document.getElementById('status').value = category.status;

    document.getElementById('categoryForm').action =
        "{{ url('accessory-categories') }}/" + category.id;

    document.getElementById('method').value = 'PUT';
}

document.getElementById('cancelEdit').addEventListener('click', function () {
    location.reload();
});

document.getElementById('search').addEventListener('keyup', function () {
    let q = encodeURIComponent(this.value.trim());

    fetch("{{ route('accessory-categories.index') }}?search=" + q, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('categoryTable').innerHTML = data.html;
    });
});
</script>
@endsection
