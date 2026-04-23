@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Department Creation')
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

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="ri-check-line me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="../assets/BG-02.jpeg" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>

            <div class="row mb-4" style="padding-top: 20px;">
                <div class="col-12 text-center">
                    <h4 class="mb-0">Create Department</h4>
                    <p class="text-muted">Establish a new organizational unit within the company.</p>
                </div>
            </div>

                <div class="card-body p-4">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="department_name" class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-building-line"></i></span>
                                    <input type="text" class="form-control" id="department_name" name="department_name" 
                                           placeholder="e.g., Information Technology" value="{{ old('department_name') }}" required>
                                </div>
                                <div class="form-text">Ensure the name is unique to avoid organizational confusion.</div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="hstack gap-2 justify-content-center">
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                    <button type="submit" class="btn btn-primary px-4" style="background-color:#1daeec; border-color: #1daeec;">
                                        <i class="ri-add-line align-bottom me-1"></i> Create Department
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="../assets/BG-02.jpeg" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
@endsection
