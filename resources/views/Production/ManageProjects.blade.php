@extends('partials.Layouts.master')

@section('title', 'Manage Projects')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        .project-status-dropdown {
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
        }
        /* Custom select styling for the status colors */
        .project-status-dropdown option { background-color: white; color: black; }
        
        .btn-soft-info { background-color: rgba(43, 166, 219, 0.1); color: #2ba6db; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-success { background-color: rgba(29, 174, 236, 0.1); color: #1daeec; border: none; }
        
        .table thead th { 
            background-color: #f8f9fa; 
            text-transform: uppercase; 
            font-size: 0.7rem; 
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .badge-employees { background: #eef2f7; color: #3b4a64; border: 1px solid #d1d9e6; }
    </style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary mb-1"><i class="ri-briefcase-4-line me-2"></i>Production Management</h4>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="ri-checkbox-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 text-uppercase d-flex align-items-center">
                <i class="ri-table-2 me-2"></i> Active Production Projects Ledger
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="projectTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client / Account</th>
                            <th>Job Specifications</th>
                            <th>Project Type</th>
                            <th>Members Assigned</th>
                            <th>Live Status</th>
                            <th>Estimated Completion Time</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statusOptions = [
                            'PVC Printing' => ['Printing', 'Welding', 'Delivery / collection'],
                            'Vinyl Printing' => ['Printing', 'Trimming', 'Pasting', 'Delivery / collection'],
                            'DTF Printing' => ['Printing', 'Trimming', 'Heat press', 'Peeling Off', 'Final Heat press', 'Delivery / collection'],
                            'Embroidery' => ['Preparation (artwork digitization & sample)', 'Embroidery', 'Trimming', 'Delivery / collection'],
                        ];

                        $statusColors = [
                            'Printing' => 'bg-primary text-white',
                            'Welding' => 'bg-info text-white',
                            'Delivery / collection' => 'bg-dark text-white',
                            'Trimming' => 'bg-warning text-dark',
                            'Pasting' => 'bg-info text-white',
                            'Heat press' => 'bg-danger text-white',
                            'Peeling Off' => 'bg-success text-white',
                            'Final Heat press' => 'bg-secondary text-white',
                            'Preparation' => 'bg-primary text-white',
                            'Embroidery' => 'bg-success text-white',
                        ];
                        @endphp

                        @foreach($projects as $project)
                            <tr>
                                <td class="fw-bold">#{{ $project->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $project->client_name }}</div>
                                    <small class="text-muted">Updated by: {{ $project->updated_by_name }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="ri-ruler-2-line"></i> {{ implode(', ', json_decode($project->sizes, true) ?? []) }}<br>
                                        <i class="ri-stack-line"></i> Qty: {{ implode(', ', json_decode($project->quantities, true) ?? []) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-primary border border-primary-subtle">
                                        {{ $project->project_type }}
                                    </span>
                                </td>
                                <td>
                                    @foreach(json_decode($project->assigned_employees, true) ?? [] as $employee)
                                        <span class="badge badge-employees rounded-sm mb-1">{{ $employee }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $availableStatuses = $statusOptions[$project->project_type] ?? [];
                                        $colorClass = $statusColors[$project->status] ?? 'bg-secondary text-white';
                                    @endphp
                                    <select class="form-select form-select-sm project-status-dropdown {{ $colorClass }}" data-project-id="{{ $project->id }}">
                                        @foreach ($availableStatuses as $status)
                                            <option value="{{ $status }}" class="bg-white text-dark" {{ $project->status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <span class="badge bg-soft-dark text-dark border-0">
                                        <i class="ri-time-line"></i> {{ $project->estimated_time }}m
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('viewProject', $project->id) }}" class="btn btn-sm btn-soft-info" title="View Job Card">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        
                                        <button class="btn btn-sm btn-soft-success complete-project-btn" data-project-id="{{ $project->id }}" title="Mark Finished">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </button>

                                        <form action="{{ route('project.destroy', $project->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger" onclick="return confirm('Archive this project?');" title="Archive">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
                                    </div>
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

@section('scripts')
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="assets/js/table/datatable.init.js"></script>
    <script type="module" src="assets/js/app.js"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#projectTable').DataTable({
                lengthChange: false,
                "pageLength": 10,
                "order": [[0, "desc"]],
                buttons: [ 'copy', 'excel', 'print' ]
            });
            table.buttons().container()
                .appendTo('#projectTable_wrapper .col-md-6:eq(0)');
            
            // Modernize the search input
            $('.dataTables_filter input').addClass('form-control border-2').css('width', '250px');
        });

        // Auto-close alerts
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                let alertElements = document.querySelectorAll(".alert");
                alertElements.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // AJAX Status Updates
        $(document).on('change', '.project-status-dropdown', function () {
            var dropdown = $(this);
            var projectId = dropdown.data('project-id');
            var newStatus = dropdown.val();

            $.ajax({
                url: '{{ route("updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    project_id: projectId,
                    status: newStatus
                },
                success: function (response) {
                    location.reload(); 
                },
                error: function () {
                    alert('Production sync failed. Please check connection.');
                }
            });
        });
    </script>
@endsection