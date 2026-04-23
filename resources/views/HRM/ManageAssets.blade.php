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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 border-start border-primary border-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">💰 Total Asset Value</h5>
                        <h4 class="text-success mb-0">ZMW {{ number_format($totalCost, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"> Rideve Media Asset Table </h5>
                     
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">View and manage all company assets, track their condition, and monitor warranty expiration dates.</p>
                        
                        <table id="buttons-datatables" class="table table-nowrap table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Asset Name</th>
                                    <th>Asset Number</th>
                                    <th>Assigned To</th>
                                    <th>Condition</th>
                                    <th>Type</th>
                                    <th>Cost</th>
                                    <th>Warranty Expiry</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Assets as $Asset)
                                    <tr>
                                        <td><span class="fw-medium">{{ $Asset->asset_name }}</span></td>
                                        <td><code>{{ $Asset->asset_number }}</code></td>
                                        <td>{{ $Asset->assigned_to }}</td>
                                        <td>
                                            @php
                                                $cond = strtolower($Asset->condition);
                                                $badge = $cond == 'new' ? 'bg-success' : ($cond == 'damaged' ? 'bg-danger' : 'bg-info');
                                            @endphp
                                            <span class="badge {{ $badge }}">{{ ucfirst($Asset->condition) }}</span>
                                        </td>
                                        <td>{{ $Asset->asset_type }}</td>
                                        <td>K{{ number_format($Asset->asset_cost, 2) }}</td>
                                        <td>{{ $Asset->warranty_expiry }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('assets.show', $Asset->id) }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                                    <li><a href="{{ route('assets.edit', $Asset->id) }}" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                    <li>
                                                        <form action="{{ route('assets.destroy', $Asset->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Confirm deletion?')">
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
        </div>
    </div>
</main>
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
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
@endsection