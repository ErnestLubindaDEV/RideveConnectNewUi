@extends('partials.Layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Add Employee Form')
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


@section('content')
<div id="layout-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
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

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
            <div class="card-header p-0"  style=" border:none;  height: 20px; ">
                <img  src="../assets/BG-02.jpeg" style=" border: none; max-width: auto; height: 15px;   padding-left:0px" alt="Pattern" class="img-fluid w-100">
            </div>
                <div class="card-header d-flex align-items-center gap-3">

                    <h5 class="card-title mb-0 flex-grow-1">Employee Registration Form</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('HRM') }}" class="btn btn-soft-secondary btn-sm">
                            <i class="ri-arrow-left-line align-bottom me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('storeEmployee') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                        @csrf

                        <div class="col-12">
                            <h6 class="text-primary text-uppercase fw-semibold">1. Personal Information</h6>
                            <hr class="mt-1">
                        </div>

                        <div class="col-md-6">
                            <label for="FirstName" class="form-label">First Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control moving-placeholder" id="FirstName" name="firstname" 
                                   placeholder="Enter First Name" value="{{ old('firstname') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="LastName" class="form-label">Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control moving-placeholder" id="LastName" name="lastname" 
                                   placeholder="Enter Last Name" value="{{ old('lastname') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phonenumber" 
                                   placeholder="+260..." value="{{ old('phonenumber') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="Email" class="form-label">Company Email</label>
                            <input type="email" class="form-control form-control-primary" id="Email" name="email" 
                                   value="@ridevemedia.com">
                            <div class="form-text">Internal use only.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="nrc" class="form-label">NRC Number</label>
                            <input type="text" class="form-control" id="nrc" name="nrc" value="{{ old('nrc') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality" value="Zambian">
                        </div>

                        <div class="col-12 mt-5">
                            <h6 class="text-primary text-uppercase fw-semibold">2. Employment Details</h6>
                            <hr class="mt-1">
                        </div>

                        <div class="col-md-6">
                            <label for="department" class="form-label">Department</label>
                            <select name="department_id" id="department" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="position" class="form-label">Job Position</label>
                            <input type="text" class="form-control" id="position" name="position" 
                                   placeholder="e.g. Web Developer" value="{{ old('position') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="contract_type" class="form-label">Contract Type</label>
                            <select id="contract_type" name="contract_type" class="form-select">
                                <option value="" selected>Choose Type...</option>
                                <option {{ old('contract_type') == 'Probational' ? 'selected' : '' }}>Probational</option>
                                <option {{ old('contract_type') == 'Intern' ? 'selected' : '' }}>Intern</option>
                                <option {{ old('contract_type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="start" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start" name="start" value="{{ old('start') }}">
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Residential Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-12 mt-5">
                            <h6 class="text-primary text-uppercase fw-semibold">3. Attachments</h6>
                            <hr class="mt-1">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">National ID (NRC Scan)</label>
                            <input type="file" class="form-control" name="national_id">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Driver License (Optional)</label>
                            <input type="file" class="form-control" name="driver_license">
                        </div>

                        <div class="col-12 mt-4">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="reset" class="btn btn-light">Reset Form</button>
                                <button type="submit" class="btn btn-primary">Create Employee Record</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
                  <div class="card-header p-0"  style=" border:none;  height: 20px; ">
                <img  src="../assets/BG-02.jpeg" style=" border: none; max-width: auto; height: 15px;   padding-left:0px" alt="Pattern" class="img-fluid w-100">
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