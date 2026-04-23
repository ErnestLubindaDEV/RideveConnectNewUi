@extends('partials.Layouts.master')

@section('title', 'Add Stock')
@section('pagetitle', 'Create New Stock Entry')
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
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif



<div class="row justify-content-center">
    <div class="col-12 col-xl-6 mx-auto">
        <div class="card" style="width:800px;">
            <div class="card-header p-0" style="margin-top:30px; border:none;">
                <img src="../assets/BG-02.jpeg" class="img-fluid w-100">
            </div>
            <div class="card-body p-4">
                <h1 style="text-align:center; color:#2ba6db !important;">Add Stock</h1>
                <form action="{{ route('storestock') }}" method="POST" class="row g-3" enctype="multipart/form-data" style="padding-top:20px">
                    @csrf
                    
                    <div class="col-md-12">
                        <label for="product_id" class="form-label">Product</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input style="border: 2px solid #333;" type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                    </div>

                    {{-- NEW COST FIELD --}}
                    <div class="col-md-12">
                        <label for="cost" class="form-label">Unit Cost (ZMW)</label>
                        <input style="border: 2px solid #333;" 
                               type="number" 
                               step="0.01" 
                               class="form-control" 
                               id="cost" 
                               name="cost" 
                               placeholder="0.00"
                               value="{{ old('cost') }}" 
                               required>
                        <small class="text-muted">Enter the purchase cost per unit.</small>
                    </div>

                    <div class="col-md-12">
                        <label for="grn_number" class="form-label">Goods Received Note (GRN) Number</label>
                        <input style="border: 2px solid #333;" 
                               type="text" 
                               class="form-control" 
                               id="grn_number" 
                               name="grn_number" 
                               value="{{ old('grn_number') }}" 
                               required>
                    </div>

                    <div class="col-md-12">
                        <div class="d-flex justify-content-center">
                            <button style="background-color:#1daeec; font-size:20px" type="submit" class="btn btn-primary px-4">Add Stock</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-header p-0" style="margin-bottom:20px; border:none;">
                <img src="../assets/BG-02.jpeg" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .btn-primary:hover {
        background-color: #aabb22;
        border-color: #aabb22;
    }
</style>