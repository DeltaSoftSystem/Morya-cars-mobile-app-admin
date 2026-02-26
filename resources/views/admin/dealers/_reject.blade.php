<div class="modal fade" id="rejectModal{{ $doc->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('admin.dealers.kyc.reject', $doc->id) }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject KYC</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <textarea name="admin_remark"
                              class="form-control"
                              rows="3"
                              placeholder="Reason for rejection"
                              required></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-danger">
                        Reject
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
