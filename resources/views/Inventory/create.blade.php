@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Pages')
@section('pagetitle', 'Blank')
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
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            
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
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-lg">
                {{-- Branded Pattern Header --}}
                <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title text-primary mb-0">
                            <i class="fas fa-box-open me-2"></i>Add New Product
                        </h4>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
                        </a>
                    </div>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            {{-- Product Name --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Product Name</label>
                                <input type="text" name="product_name" class="form-control border-primary" 
                                       placeholder="Enter product name" value="{{ old('product_name') }}" required>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="" disabled selected>Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Price --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Price (ZMW)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">K</span>
                                    <input type="number" name="price" class="form-control" 
                                           placeholder="0.00" min="0" step="0.01">
                                </div>
                            </div>

                            {{-- Image Upload with LFM --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Item Images</label>
                                <div class="input-group mb-2">
                                    <button id="lfm" data-input="image_path" data-preview="holder" class="btn btn-primary">
                                        <i class="fas fa-image me-1"></i> Choose from Gallery
                                    </button>
                                    <input id="image_path" class="form-control" type="text" name="image" placeholder="Image URL will appear here...">
                                </div>
                                <div id="holder" class="mt-2" style="max-height:150px;"></div>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="description" rows="3" 
                                          placeholder="Enter product details...">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-12 pt-3">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg shadow">
                                        <i class="fas fa-plus-circle me-1"></i> Save Product to Inventory
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Branded Pattern Footer --}}
                <div class="card-footer p-0 mt-3" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $(document).ready(function () {
        // Initialize Laravel File Manager
        $('#lfm').filemanager('image');

        // Optional: Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $(".alert").fadeOut('slow');
        }, 5000);
    });
</script>
@endsection