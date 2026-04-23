@extends('partials.Layouts.master')
@section('title', 'Create Category')
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
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-end mb-3">
    <div class="btn-group">
        <a href="{{ route('Inventory') }}" class="btn btn-primary" style="background-color:#ccdb2d; border-color: #ccdb2d; color: black; font-weight: 600;">
            <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-6 mx-auto">
        <div class="card" style="width:800px; border: none; shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div class="card-header p-0" style="margin-top:30px; border:none;">
                <img src="../assets/BG-02.jpeg" class="img-fluid w-100" alt="Header Background">
            </div>
            
            <div class="card-body p-4">
                <h1 style="text-align:center; color:#2ba6db !important; font-weight: 700; margin-bottom: 10px;">Create Category</h1>
                <p class="text-center text-muted">Define new classifications for inventory and raw materials.</p>
                
                <form action="{{route('categories.store')}}" method="POST" class="row g-3" style="padding-top:20px">
                    @csrf
                    
                    <div class="col-md-12">
                        <label for="name" class="form-label fw-bold">Category Name</label>
                        <input style="border: 2px solid #333;" type="text" class="form-control" id="name" name="name" placeholder="Enter Category Name (e.g., Vinyl, PVC, Ink)" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-12">
                        <label for="parent_id" class="form-label fw-bold">Parent Category</label>
                        <select class="form-select" id="parent_id" name="parent_id" style="border: 1px solid #ced4da;">
                            <option value="">None (Primary Category)</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="type" class="form-label fw-bold">Category Type</label>
                        <select class="form-select" id="type" name="type" required style="border: 1px solid #ced4da;">
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="raw_material" {{ old('type') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                            <option value="finished_goods" {{ old('type') == 'finished_goods' ? 'selected' : '' }}>Finished Goods</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="d-flex justify-content-center">
                            <button style="background-color:#1daeec; border-color: #1daeec; font-size:20px; font-weight: 600;" type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                                <i class="ri-save-line me-1"></i> Create Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-header p-0" style="margin-bottom:20px; border:none;">
                <img src="../assets/BG-02.jpeg" class="img-fluid w-100" alt="Footer Background">
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-primary:hover {
        background-color: #aabb22 !important;
        border-color: #aabb22 !important;
        color: black !important;
    }
    .form-label {
        color: #333;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            let alertElements = document.querySelectorAll(".alert");
            alertElements.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection