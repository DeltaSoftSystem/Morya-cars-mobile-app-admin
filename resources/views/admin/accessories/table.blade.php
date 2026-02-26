<table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th width="60">Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Discounted</th>
            <th>Stock</th>
            <th>Status</th>
            <th width="120">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($accessories as $accessory)
            <tr>
                <td>
                    @if($accessory->image)
                        <img src="{{ asset('storage/'.$accessory->image) }}"
                             width="50" height="50" class="rounded">
                    @else
                        —
                    @endif
                </td>

                <td>
                    <strong>{{ $accessory->name }}</strong>
                    @if($accessory->brand)
                        <br><small class="text-muted">{{ $accessory->brand }}</small>
                    @endif
                </td>

                <td>{{ $accessory->category->name ?? '-' }}</td>

                <td>₹{{ number_format($accessory->price, 2) }}</td>

                <td class="text-success">
                    ₹{{ number_format($accessory->discounted_price, 2) }}
                </td>

                <td>
                    @if($accessory->stock > 0)
                        <span class="badge badge-success">{{ $accessory->stock }}</span>
                    @else
                        <span class="badge badge-danger">Out</span>
                    @endif
                </td>

                <td>
                    @if($accessory->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </td>

                <td>
                    <div class="btn-group">
                    <a href="{{ route('accessories.show', $accessory->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('accessories.edit', $accessory->id) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('accessories.destroy', $accessory->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this accessory?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">
                    No accessories found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div id="paginationLinks">
    {{ $accessories->links() }}
</div>
