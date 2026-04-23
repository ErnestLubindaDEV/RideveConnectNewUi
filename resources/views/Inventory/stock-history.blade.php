@extends('partials.layouts.master')

@section('title', 'Stock History | RideveConnect')
@section('title-sub', 'Warehouse')
@section('pagetitle', 'Stock History')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/nouislider/nouislider.min.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    
    <style>
        .badge-addition { background-color: rgba(43, 166, 219, 0.1); color: #2ba6db; border: 1px solid #2ba6db; }
        .badge-deduction { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: 1px solid #f06548; }
        .qty-cell { font-weight: 600; font-size: 14px; }
    </style>
@endsection

@section('content')
<div id="layout-wrapper">
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Movement History & Audit Trail</h5>
                    <a href="{{ route('Inventory') }}" class="btn btn-sm" style="background-color:#ccdb2d; border-color: #ccdb2d; color: black;">
                        <i class="ri-arrow-left-line"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Product</th>
                                <th>Qty Added</th>
                                <th>Qty Deducted</th>
                                <th>Unit Cost (ZMW)</th>
                                <th>GRN #</th>
                                <th>Added By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockHistories as $history)
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>
                                        <span class="badge {{ $history->type === 'Addition' ? 'badge-addition' : 'badge-deduction' }}">
                                            {{ $history->type }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $history->product->name }}</td>
                                    <td class="qty-cell text-success">
                                        {{ $history->quantity_added > 0 ? '+' . $history->quantity_added : '-' }}
                                    </td>
                                    <td class="qty-cell text-danger">
                                        {{ $history->stock_deducted > 0 ? '-' . $history->stock_deducted : '-' }}
                                    </td>
                                    <td class="fw-bold">
                                        {{ number_format($history->cost, 2) }}
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $history->grn_number ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ri-user-settings-line me-1 text-primary"></i>
                                            {{ $history->added_by }}
                                        </div>
                                    </td>
                                    <td class="small">{{ $history->created_at->format('d M Y, H:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        if ($('#buttons-datatables').length) {
            $('#buttons-datatables').DataTable({
                destroy: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                responsive: true,
                order: [[0, 'desc']] // Show latest history first
            });
        }
    });
</script>

<script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection