@extends('partials.Layouts.master')
@section('title', 'Manage Employees')

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
@endsection

@section('content')
 <div id="layout-wrapper">
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
                    <img src="../assets/BG-02.jpeg" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
                    <div class="card-header">
                        <h5 class="card-title mb-0"> Leave Management Table </h5>
                    </div>
                    <div class="card-body">

                        <p class="text-muted mb-4">View and manage leav Applications.</p>
                        
                
                <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveApplications as $leave)
                            <tr>
                                <td>{{ $leave->id }}</td>
                                <td>{{ $leave->full_name }}</td>
                                <td>{{ $leave->leave_type }}</td>
                                <td>{{ $leave->leave_duration }} days</td>
                                <td>{{ $leave->leave_from }}</td>
                                <td>{{ $leave->leave_to }}</td>
                                <td>
                                    @if($leave->status == 'Pending')
                                        <span class="badge bg-warning text-dark">{{ $leave->status }}</span>
                                    @elseif(in_array($leave->status, ['Supervisor Approved', 'Ongoing', 'Approved']))
                                        <span class="badge bg-success">{{ $leave->status }}</span>
                                    @elseif($leave->status == 'Rejected')
                                        <span class="badge bg-danger">{{ $leave->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $leave->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('leave.details', $leave->id) }}" class="btn btn-sm btn-info">View</a>
                                        
                                        @if (!in_array($leave->status, ['Approved', 'Ongoing', 'Completed']))
                                            <a href="{{ route('leave.approval.view', $leave->id) }}" class="btn btn-sm btn-success">Approve</a>
                                        @endif
<form action="{{ route('leave.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="ri-delete-bin-line"></i> Delete
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