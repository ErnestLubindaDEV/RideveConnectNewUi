@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human resource management')
@section('pagetitle', 'Employee Profile')
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
        /* Fix for dropdowns being cut off in responsive tables */
        .dataTables_wrapper .table-responsive {
            overflow: visible !important;
        }
    </style>
@endsection
@section('content')

@if ($errors->any())
    <div class="alert alert-danger" id="errorAlert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
    <div id="layout-wrapper">
        {{-- Profile Header Card --}}
        <div class="card overflow-hidden">
            <!-- <div class="card-body h-176px"
                style="background-image: url('{{ asset('assets/images/background.png') }}'); background-repeat: no-repeat; background-position: right; background-size: cover;">
            </div> -->
            <div class="mt-2">
                <div class="card-body p-5">
                    <div class="d-flex flex-wrap align-items-start gap-5">
                       <div class="mt-n12 flex-shrink-0">
                            <div class="position-relative d-inline-block">
                                <img src="{{ ($employee->user && $employee->user->profile_picture) 
                                            ? asset('storage/' . $employee->user->profile_picture) 
                                            : asset('assets/images/avatar/avatar-1.png') }}" 
                                    alt="Avatar"
                                    class="h-128px w-128px border border-4 border-white shadow-lg rounded-3">
                                
                                <span class="position-absolute profile-dot bg-success rounded-circle">
                                    <span class="visually-hidden">Active</span>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="mb-5">
                                <h5 class="mb-1">{{ $employee->full_name }} <i class="bi bi-patch-check-fill fs-16 ms-1 text-info"></i></h5>
                                <p class="text-muted fs-12 mb-0">{{ $employee->position }} | {{ $employee->department }}</p>
                            </div>
                            <div class="w-50 border-dashed border border-1">
                                <div class="p-4 d-flex">
                                    <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center border-end border-dark border-opacity-20">
                                        <h5 class="mb-0 lh-1">{{ $employee->leave_days }}</h5>
                                        <span class="text-muted lh-sm fs-12">Leave Days</span>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center border-end border-dark border-opacity-20">
                                        <h5 class="mb-0 lh-1">{{ $employee->contract_type }}</h5>
                                        <span class="text-muted lh-sm fs-12">Contract</span>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center">
                                        <h5 class="mb-0 lh-1">{{ \Carbon\Carbon::parse($employee->start_date)->format('Y') }}</h5>
                                        <span class="text-muted lh-sm fs-12">Joined</span>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center">
                                        <h5 class="mb-0 lh-1">{{ $employee->gender }}</h5>
                                        <span class="text-muted lh-sm fs-12">Gender</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mt-2">
                                <div class="col-md-4 col-xl-3">
                                    <div class="d-flex gap-2 text-muted mb-2"><i class="bi bi-mailbox fs-16"></i><p class="mb-0">Email</p></div>
                                    <h6 class="mb-0">{{ $employee->email }}</h6>
                                </div>
                                <div class="col-md-4 col-xl-3">
                                    <div class="d-flex gap-2 text-muted mb-2"><i class="bi bi-telephone fs-16"></i><p class="mb-0">Phone No</p></div>
                                    <h6 class="mb-0">{{ $employee->phone_number }}</h6>
                                </div>
                                <div class="col-md-4 col-xl-3">
                                    <div class="d-flex gap-2 text-muted mb-2"><i class="bi bi-person-vcard fs-16"></i><p class="mb-0">NRC Number</p></div>
                                    <h6 class="mb-0">{{ $employee->NRC }}</h6>
                                </div>
                                 <div class="col-md-4 col-xl-3">
                                    <div class="d-flex gap-2 text-muted mb-2"><i class="bi bi-house fs-16"></i><p class="mb-0">Address</p></div>
                                    <h6 class="mb-0">{{ $employee->address }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <ul class="nav nav-pills" id="employeeTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-tab-pane" type="button" role="tab">Overview</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-tab-pane" type="button" role="tab">Documents & ID</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-tab-pane" type="button" role="tab">
            <i class="bi bi-gear me-1"></i> Edit Profile
        </button>
    </li>
</ul>

                <div class="tab-content" id="employeeTabContent">
                    {{-- Tab 1: Overview --}}
                    <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="row">
                            {{-- Sidebar info --}}
                            <div class="col-12 col-xl-4">
                                <div class="card h-100">
                                    <div class="card-header"><h5 class="mb-0">Employment Status</h5></div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label class="text-muted fs-12 d-block">Department</label>
                                            <h6 class="mb-0">{{ $employee->department }}</h6>
                                        </div>
                                        <div class="mb-4">
                                            <label class="text-muted fs-12 d-block">Joined Date</label>
                                            <h6 class="mb-0">{{ \Carbon\Carbon::parse($employee->start_date)->format('d M, Y') }}</h6>
                                        </div>
                                        <div>
                                            <label class="text-muted fs-12 d-block">Nationality</label>
                                            <h6 class="mb-0">{{ $employee->nationality }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Main details --}}
                            <div class="col-12 col-xl-8">
                                <div class="card h-100">
                                    <div class="card-header"><h5 class="mb-0">Full Profile Details</h5></div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="text-muted mb-1">Full Name</label>
                                                <p class="fw-bold">{{ $employee->full_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-muted mb-1">Date of Birth</label>
                                                <p class="fw-bold">{{ \Carbon\Carbon::parse($employee->dob)->format('d F, Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-muted mb-1">License Number</label>
                                                <p class="fw-bold">{{ $employee->license_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-muted mb-1">Contract Type</label>
                                                <div><span class="badge bg-info-subtle text-info fs-12">{{ $employee->contract_type }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        
                        </div> {{-- End Row --}}
                    </div> {{-- End Tab Overview --}}

                    {{-- Tab 2: Documents --}}
                    <div class="tab-pane fade" id="documents-tab-pane" role="tabpanel" aria-labelledby="documents-tab">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Identification Documents</h5></div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <h6>National ID (NRC)</h6>
                                        <div class="p-3 border rounded bg-light">
                                            @if($employee->national_id)
                                                <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                                <p class="mt-2"><a href="{{ asset($employee->national_id) }}" target="_blank" class="btn btn-sm btn-primary">View Document</a></p>
                                            @else
                                                <p class="text-muted">No document uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Driver's License</h6>
                                        <div class="p-3 border rounded bg-light">
                                            @if($employee->driver_license)
                                                <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                                <p class="mt-2"><a href="{{ asset($employee->driver_license) }}" target="_blank" class="btn btn-sm btn-primary">View Document</a></p>
                                            @else
                                                <p class="text-muted">No document uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> {{-- End Tab Documents --}}

                    {{-- Tab 3: Edit Profile (Settings) --}}
<div class="tab-pane fade" id="settings-tab-pane" role="tabpanel" aria-labelledby="settings-tab">
    <div class="card">
           <div class="card-header p-0"  style=" border:none;  height: 20px; ">
                <img  src="{{asset('assets/BG-02.jpeg')}}" style=" border: none; max-width: auto; height: 15px;   padding-left:0px" alt="Pattern" class="img-fluid w-100">
            </div>
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Employee Record</h5>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('employee.update', ['employee_id' => $employee->employee_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') {{-- Ensure your route matches this method --}}

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="Full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="Full_name" name="full_name" value="{{ old('full_name', $employee->full_name) }}">
                    </div>

                    <div class="col-lg-3">
                        <label for="leave_days" class="form-label">Leave Days</label>
                        <input type="number" class="form-control" id="leave_days" name="leave_days" value="{{ old('leave_days', $employee->leave_days) }}">
                    </div>

                    <div class="col-lg-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone_number" value="{{ old('phone_number' , $employee->phone_number) }}">
                    </div>

                    <div class="col-lg-6">
                        <label for="Email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="Email" name="email" value="{{ old('email', $employee->email) }}">
                    </div>

                    <div class="col-lg-3">
                        <label for="nrc" class="form-label">NRC Number</label>
                        <input type="text" class="form-control" id="nrc" name="nrc" value="{{ old('nrc', $employee->NRC) }}">
                    </div>

                    <div class="col-lg-3">
                        <label for="Licence" class="form-label">Driver's Licence Number</label>
                        <input type="text" class="form-control" id="Licence" name="license_number" value="{{ old('license_number', $employee->license_number) }}">
                    </div>


                       <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <div class="position-relative input-icon">
                        <select name="department" id="department" class="form-control">
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>

                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position) }}">
                    </div>

                    <div class="col-lg-3">
                        <label for="contract_type" class="form-label">Contract Type</label>
                        <select name="contract_type" class="form-select">
                            <option value="Probational" {{ old('contract_type', $employee->contract_type) == 'Probational' ? 'selected' : '' }}>Probational</option>
                            <option value="Intern" {{ old('contract_type', $employee->contract_type) == 'Intern' ? 'selected' : '' }}>Intern</option>
                            <option value="Permanent" {{ old('contract_type', $employee->contract_type) == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label for="Dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="Dob" name="dob" value="{{ old('dob', $employee->dob) }}">
                    </div>

                    <div class="col-lg-6">
                        <label for="start" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start" name="start_date" value="{{ old('start_date', $employee->start_date) }}">
                    </div>

                    <div class="col-6">
                        <label for="address" class="form-label">Residential Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" name="nationality" value="{{ old('nationality', $employee->nationality) }}" placeholder="e.g. Zambian" required>
                    </div>

                    <div class="col-12">
                        <label for="driver_license" class="form-label">Update Driver's License (PDF)</label>
                        <input type="file" class="form-control" id="driver_license" name="driver_license" accept=".pdf">
                        <div class="form-text">Current file: {{ basename($employee->driver_license) ?? 'None' }}</div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Employee Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
                </div> {{-- End Tab Content --}}
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ asset('assets/js/table/datatable.init.js') }}"></script>
<script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection