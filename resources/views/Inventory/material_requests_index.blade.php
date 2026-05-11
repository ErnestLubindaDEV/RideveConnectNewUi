@extends('partials.Layouts.master')

@section('title', 'Material Requests | RideveConnect')
@section('title-sub', 'Production')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        #signature-pad { background-color: #f8f9fa; cursor: crosshair; touch-action: none; }
        .badge-item { background-color: rgba(52, 58, 64, 0.1); color: #343a40; border: 1px solid #343a40; font-weight: 500; }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Material Requests Table</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="materialRequestsTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Requested By</th>
                                <th>Items & Quantities</th>
                                <th>Purpose</th>
                                <th>Requested On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td class="fw-medium">{{ $request->requested_by }}</td>
                                <td>
                                    @foreach($request->items as $item)
                                        <span class="badge badge-item mb-1">
                                            {{ $item->product->name ?? 'Unknown' }} — {{ $item->quantity }}
                                        </span><br>
                                    @endforeach
                                </td>
                                <td>{{ $request->purpose }}</td>
                                <td class="small">{{ $request->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if(strtolower($request->status ?? 'pending') == 'pending')
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal" 
                                                data-request-id="{{ $request->id }}">
                                            Approve
                                        </button>
                                    @else
                                        <span class="badge bg-light text-success border">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" id="approvalForm" action="{{ route('ProductionRequest.approve') }}">
            @csrf
            <input type="hidden" name="request_id" id="modal_request_id">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Approve Material Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-4"><strong>Store Manager:</strong> {{ auth()->user()->name }}</p>
                    
                    <div class="mb-3">
                        <label for="collecting_staff" class="form-label fw-bold">Collecting Staff Member Name</label>
                        <input style="border: 2px solid #333;" type="text" class="form-control" name="collecting_staff" id="collecting_staff" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Digital Signature</label>
                        <div class="text-center">
                            <canvas id="signature-pad" class="border border-dark rounded w-100" height="200"></canvas>
                            <input type="hidden" name="signature" id="signature-input">
                            <button type="button" class="btn btn-sm btn-link text-danger mt-2" id="clear-signature">
                                <i class="ri-delete-bin-line"></i> Clear Signature
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" style="background-color:#1daeec; border-color:#1daeec; font-size:18px;">Confirm Approval</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    let signaturePad, canvas;

    $(document).ready(function() {
        // Init DataTable
        if ($('#materialRequestsTable').length) {
            $('#materialRequestsTable').DataTable({
                responsive: true,
                lengthChange: false
            });
        }

        // Init Signature Pad
        canvas = document.getElementById('signature-pad');
        if (canvas) {
            signaturePad = new SignaturePad(canvas);
        }

        function resizeCanvas() {
            if (!canvas) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        // Resize when modal opens to ensure drawing works
        $('#approveModal').on('shown.bs.modal', function () {
            resizeCanvas();
        });

        $('#clear-signature').on('click', function () {
            if (signaturePad) signaturePad.clear();
        });

        // Use event delegation to catch ID from table buttons
        $(document).on('click', '[data-bs-target="#approveModal"]', function () {
            const requestId = $(this).data('request-id');
            $('#modal_request_id').val(requestId);
        });

        // Capture signature on submit
        $('#approvalForm').on('submit', function (e) {
            if (!signaturePad || signaturePad.isEmpty()) {
                alert("Please provide a digital signature.");
                e.preventDefault();
            } else {
                $('#signature-input').val(signaturePad.toDataURL());
            }
        });

        // Auto-dismiss alerts
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection