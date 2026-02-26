<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Applies To</th>
            <th>Discount</th>
            <th>Validity</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($offers as $offer)
        <tr>
            <td>{{ $offer->title }}</td>
            <td>{{ ucfirst(str_replace('_',' ', $offer->applies_to)) }}</td>
            <td>
                {{ $offer->discount_type === 'percentage'
                    ? $offer->discount_value.'%'
                    : '₹'.$offer->discount_value }}
            </td>
            <td>
                
                    {{ \Carbon\Carbon::parse($offer->start_date)->format('d-m-Y') }}
                    →
                    {{ \Carbon\Carbon::parse($offer->end_date)->format('d-m-Y') }}
            </td>

            <td>
                <span class="badge {{ $offer->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $offer->is_active ? 'Active' : 'Disabled' }}
                </span>
            </td>
            <td>
                <a href="{{ route('offers.edit', $offer) }}"
                   class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('offers.toggle', $offer) }}"
                    method="POST"
                    class="d-inline">
                    @csrf
                    <button
                        class="btn btn-sm {{ $offer->is_active ? 'btn-success' : 'btn-danger' }}">
                        {{ $offer->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </form>

                <form action="{{ route('offers.destroy', $offer) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Delete this offer?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                         <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                No offers found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div id="paginationLinks">
    {{ $offers->links() }}
</div>
