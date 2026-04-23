@extends('partials.Layouts.master')
@section('title', 'Attendance Reports')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    
    <style>
        .btn-custom-back { background-color: #ccdb2d; color: black; border: none; }
        .btn-custom-back:hover { background-color: #b8c628; color: black; }
        /* Ensure buttons look professional */
        .dt-buttons .btn { margin-right: 5px; border-radius: 4px; }
    </style>
@endsection

@section('content')
<div id="layout-wrapper">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daily Attendance Logs</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('attendance.sync') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="ri-refresh-line"></i> Sync Device
                        </a>
                        <a href="{{ route('HRM') }}" class="btn btn-sm btn-custom-back">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <p class="text-muted mb-4">Live office attendance tracked automatically. View daily clock-in times or download a report for your records.</p>
                    
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Employee Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                           <tbody>
    @foreach($attendances as $record)
        {{-- Skip records where employee_id is 0 --}}
        @if($record->employee_id == 0)
            @continue
        @endif

        <tr>
            <td>{{ $record->work_date->format('d M, Y') }}</td>
            <td>
                <span class="fw-bold text-dark">
                    {{ $record->employee->full_name ?? 'ID: '.$record->employee_id }}
                </span>
            </td>
            <td><span class="text-success"><i class="ri-login-box-line me-1"></i></span> {{ $record->check_in ? $record->check_in->format('H:i') : '--:--' }}</td>
            <td><span class="text-danger"><i class="ri-logout-box-line me-1"></i></span> {{ $record->check_out ? $record->check_out->format('H:i') : '--:--' }}</td>
            <td><span class="badge bg-light text-dark border">{{ $record->formatted_duration }}</span></td>
            <td>
                @if($record->check_in && !$record->check_out)
                    <span class="badge bg-warning-subtle text-warning">On Shift</span>
                @elseif($record->total_hours >= 8)
                    <span class="badge bg-success-subtle text-success">Full Day</span>
                @else
                    <span class="badge bg-info-subtle text-info">Short Shift</span>
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
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        if ($('#buttons-datatables').length) {
            $('#buttons-datatables').DataTable({
                destroy: true,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', className: 'btn btn-soft-secondary' },
                    { extend: 'excel', className: 'btn btn-soft-success' },
                    { extend: 'pdf', className: 'btn btn-soft-danger' },
                    { extend: 'print', className: 'btn btn-soft-info' }
                ],
                order: [[0, 'desc']],
                responsive: true
            });
        }
    });
</script>

<script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection