@extends('partials.layouts.master')

@section('title', 'Inventory Management | RideveConnect')

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
        .img-thumbnail-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            cursor: zoom-in;
            object-fit: cover;
            border: 1px solid #eee;
        }
        .img-thumbnail-hover:hover {
            transform: scale(4);
            z-index: 999;
            position: relative;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .btn-inventory {
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
    </style>
@endsection

@section('content')
<div id="layout-wrapper">
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"> Inventory Table</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-info btn-inventory">Add Item</a>
                        <a href="{{ route('stock') }}" class="btn btn-sm btn-info btn-inventory">Add Stock</a>
                        <a href="{{ route('category') }}" class="btn btn-sm btn-info btn-inventory">Add Category</a>
                        <a href="{{ route('Inventory') }}" class="btn btn-sm btn-secondary" style="background-color:#ccdb2d; border-color: #ccdb2d; color: black;">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price (ZMW)</th>
                                <th>Images</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        <h6 class="mb-0 fs-14">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($product->stock)
                                            <span class="fw-bold">{{ $product->stock->quantity }} units</span>
                                        @else
                                            <span class="text-danger">No stock data</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @php
                                            $images = json_decode($product->images, true);
                                            $firstImage = !empty($images) ? $images[0] : null;
                                            $displayPath = $firstImage ? 'storage/' . ltrim(str_replace('http://localhost/storage/', '', $firstImage), 'storage/') : null;
                                        @endphp
                                        @if ($displayPath)
                                            <img src="{{ asset($displayPath) }}" alt="Product" width="40" height="40" class="img-thumbnail-hover">
                                        @else
                                            <span class="text-muted small">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $product->type)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                destroy: true,
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