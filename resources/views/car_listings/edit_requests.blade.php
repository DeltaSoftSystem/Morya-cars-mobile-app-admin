<table class="table table-bordered">
    <thead>
        <tr>
            <th>Car</th>
            <th>Seller</th>
            <th>Changes</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($requests as $req)
        <tr>
            <td>{{ $req->carListing->title }}</td>
            <td>{{ $req->user->name }}</td>
            <td>
                <ul class="list-unstyled">
                    @foreach($req->changes as $field => $values)
                        <li>
                            <strong>{{ ucfirst(str_replace('_',' ', $field)) }}</strong> :
                            <span class="text-danger">{{ $values['old'] }}</span>
                            →
                            <span class="text-success">{{ $values['new'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </td>
            <td>
                <form method="POST" action="{{ route('admin.car-listings.edit-approve', $req->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                </form>

                <form method="POST" action="{{ route('admin.car-listings.edit-reject', $req->id) }}" class="mt-1">
                    @csrf
                    <input type="text" name="reason" class="form-control mb-1"
                           placeholder="Reject reason" required>
                    <button class="btn btn-danger btn-sm">Reject</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $requests->links() }}
