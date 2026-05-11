@extends('partials.Layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Department Management')
@section('css')
<link rel="stylesheet" href="{{ asset('../assets/libs/air-datepicker/air-datepicker.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<link rel="stylesheet" href="../assets/libs/simplebar/simplebar.min.css">
<link href="../assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet">
<link href="../assets/libs/nouislider/nouislider.min.css" rel="stylesheet">
<link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
<link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div id="layout-wrapper">
        
    

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="ri-check-line me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                         <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="../assets/BG-02.jpeg" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="card-title mb-0"> Department Administration </h1>
                        <a href="{{ route('HRM') }}" class="btn btn-sm" style="background-color:#ccdb2d; color: black;">
                            <i class="ri-arrow-left-line align-middle"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
<p class="text-muted">Review, organize, and manage organizational units and their structures.</p>                        
                        <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th>Department Name</th>
                                    <th>Department Manager/Supervisor</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr>
                                        <td><code>#{{ $department->id }}</code></td>
                                        <td class="fw-medium text-primary">{{ $department->name }}</td>
                                        <td>{{ $department->supervisor_name }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('department.manage', $department->id) }}" class="dropdown-item">
                                                            <i class="ri-settings-3-line align-bottom me-2 text-muted"></i> Manage
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('department.delete', $department->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this department?');">
                                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
            </div>
        </div></div></main>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ asset('assets/js/table/datatable.init.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Auto-dismiss alerts
            setTimeout(function() {
                $(".alert").fadeOut('slow');
            }, 5000);
        });
    </script>
    
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection