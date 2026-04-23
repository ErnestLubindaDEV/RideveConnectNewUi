@extends('partials.layouts.master')

@section('title', 'Depatment Management')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Edit Department Form')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
@endsection
@section('content')

@section('content')
<div id="layout-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            
            {{-- Feedback Alerts --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                {{-- Top Pattern Header --}}
                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>

                <div class="card-header d-flex align-items-center gap-3">
                    <h5 class="card-title mb-0 flex-grow-1" style="color:#2ba6db;">Management: Edit Department</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('HRM') }}" class="btn btn-sm" style="background-color:#ccdb2d; color: black; font-weight: 500;">
                            <i class="ri-arrow-left-line align-bottom me-1"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('department.update', $department->id) }}" method="POST" class="row g-4">
                        @csrf
                        @method('POST') {{-- Ensure your route matches this, or use @method('PUT') if standard --}}

                        <!-- <div class="col-12">
                            <h6 class="text-primary text-uppercase fw-semibold">Department Settings</h6>
                            <hr class="mt-1">
                        </div> -->

                        <div class="col-md-12">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control bg-light" id="department_name" name="department_name" 
                                   value="{{ old('department_name', $department->name) }}" readonly>
                            <div class="form-text text-muted">Department names are managed by system admins.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="supervisor_id" class="form-label">Assign Supervisor <span class="text-danger">*</span></label>
                            <select class="form-select border-dark" id="supervisor_id" name="supervisor_id" required>
                                <option value="">-- Choose Staff Member --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_id }}" @if ($department->supervisor_id == $employee->employee_id) selected @endif>
                                        {{ $employee->full_name }} ({{ $employee->position }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mt-4">
                            <label class="form-label fw-bold">Active Staff in {{ $department->name }}</label>
                            <div class="list-group border-dark">
                                @forelse ($employees as $employee)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-medium">{{ $employee->full_name }}</span>
                                            <br><small class="text-muted">{{ $employee->position }}</small>
                                        </div>
                                        <span class="badge rounded-pill bg-soft-info text-info">Active</span>
                                    </div>
                                @empty
                                    <div class="list-group-item text-muted text-center py-3">No employees currently assigned.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="hstack gap-2 justify-content-center">
                                <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm" style="background-color:#1daeec; border-color:#1daeec;">
                                    Save Changes <i class="ri-check-line ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Bottom Pattern Footer --}}
                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection@section('js')
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