@extends('partials.Layouts.master')
@section('title', 'Production History')

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
        .table thead th { 
            background-color: #f8f9fa; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .badge-completed {
            background-color: rgba(5, 176, 133, 0.1);
            color: #05b085;
            border: 1px solid rgba(5, 176, 133, 0.2);
        }
        .badge-type {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-dark mb-1"><i class="ri-archive-line me-2 text-success"></i>Completed Projects</h4>
            <p class="text-muted small mb-0">Review historically finalized production jobs and fulfillment dates.</p>
        </div>
        <div class="btn-group shadow-sm">
    
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="ri-checkbox-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Job Specifications</th>
                            <th>Project Type</th>
                            <th>Assigned Team</th>
                            <th>Completion Date</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedProjects as $project)
                            <tr>
                                <td class="text-muted fw-bold">#{{ $project->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $project->client_name }}</div>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="ri-ruler-line"></i> {{ implode(', ', json_decode($project->sizes, true) ?? []) }}<br>
                                        <i class="ri-hashtag"></i> {{ implode(', ', json_decode($project->quantities, true) ?? []) }} Units
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-type rounded-pill px-3">
                                        {{ $project->project_type }}
                                    </span>
                                </td>
                                <td>
                                    @php $team = json_decode($project->assigned_employees, true) ?? []; @endphp
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($team as $employee)
                                            <span class="badge bg-light text-dark border">{{ $employee }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="ri-calendar-check-line text-success"></i> 
                                        {{ \Carbon\Carbon::parse($project->updated_at)->format('d M, Y') }}<br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($project->updated_at)->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-completed">
                                        <i class="ri-check-double-line"></i> Finalized
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                destroy: true, // Prevents the re-initialization error
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                responsive: true
            });
        }
    });
</script>

<script src="{{ asset('assets/js/table/datatable.init.js') }}"></script>
<script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection