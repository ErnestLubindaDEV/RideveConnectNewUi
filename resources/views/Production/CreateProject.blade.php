@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Production')
@section('pagetitle', 'Create Project Form')
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

<div class="container-fluid mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-primary"><i class="ri-rocket-2-line me-2"></i>Launch New Production</h4>
       
       
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" id="errorAlert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header p-0 overflow-hidden">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" class="img-fluid w-100" style="height: 12px; object-fit: cover;">
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form id="stockForm" action="{{ route('projects.store') }}" method="POST" class="row g-4" enctype="multipart/form-data">
                        @csrf

                        <div class="col-12 border-bottom pb-2">
                            <h5 class="text-secondary d-flex align-items-center">
                                <i class="ri-folder-info-line me-2 text-primary"></i> Project Details
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <label for="artwork" class="form-label fw-bold">Artwork / Blueprint</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="ri-image-add-line"></i></span>
                                <input type="file" class="form-control" id="artwork" name="artwork" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="creator" class="form-label fw-bold">Launched By</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="ri-user-smile-line"></i></span>
                                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div> 

                        <div class="col-md-6">
                            <label for="client_name" class="form-label fw-bold">Client Name</label>
                            <input type="text" class="form-control border-2" id="client_name" name="client_name" placeholder="Enter Client or Company" value="{{ old('client_name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="project_type" class="form-label fw-bold">Production Type</label>
                            <select class="form-select border-2" id="project_type" name="project_type" required>
                                <option value="" disabled selected>-- Select Type --</option>
                                <option value="Embroidery">Embroidery</option>
                                <option value="DTF Printing">DTF Printing</option>
                                <option value="PVC/Flex Printing">PVC Printing</option>
                                <option value="Vinyl Printing">Vinyl Printing</option>
                                <option value="Paper Printing">Paper Printing</option>
                                <option value="Video Flex Printing">Video Flex Printing</option>
                                <option value="Screen Printing">Screen Printing</option>
                                <option value="Pull up Print">Pull up Banner Print</option>
                                <option value="Backdrop Banner">Backdrop Banner</option>
                                <option value="Telescopic Flags">Telescopic Flags</option>
                                <option value="Cut and Print">Cut and Print</option>
                            </select>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label fw-bold d-flex justify-content-between">
                                Dimensions / Sizes
                                <small class="text-muted">Format: Width x Height</small>
                            </label>
                            <div id="size-fields">
                                <div class="d-flex mb-2 gap-2 align-items-center">
                                    <input type="text" name="sizes[]" placeholder="e.g. 2M x 1M" class="form-control border-2" required>
                                    <input type="number" class="form-control border-2" name="quantities[]" placeholder="Qty" style="width: 120px;" required>
                                    <button type="button" class="btn btn-outline-danger btn-icon remove-size"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </div>
                            <button type="button" id="add-size" class="btn btn-soft-primary btn-sm mt-1">
                                <i class="ri-add-line me-1"></i> Add Dimension
                            </button>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-bold">Assign Production Team</label>
                            <div class="custom-dropdown border-2 rounded p-2 bg-white shadow-sm" style="cursor: pointer; position: relative;">
                                <div id="employeeToggle" class="d-flex justify-content-between align-items-center">
                                    <span id="toggleText" class="text-muted">Select Staff...</span>
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                                <div class="dropdown-menu-custom w-100 shadow border rounded" id="employeeMenu">
                                    @foreach ($employees as $employee)
                                        <div class="p-2 border-bottom hover-bg-light">
                                            <div class="form-check">
                                                <input type="checkbox" name="assigned_to[]" value="{{ $employee->full_name }}" class="form-check-input employee-checkbox" id="emp_{{ $loop->index }}">
                                                <label class="form-check-label w-100" for="emp_{{ $loop->index }}">{{ $employee->full_name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="selectedEmployees" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="ri-time-line me-1"></i> Estimated Production Duration</label>
                            <div class="row g-2 bg-light p-3 rounded">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control border-0" id="estimated_hours" name="estimated_hours" placeholder="Hours" required min="0">
                                        <label for="estimated_hours">Hours</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control border-0" id="estimated_minutes" name="estimated_minutes" placeholder="Minutes" required min="0" max="59" value="0">
                                        <label for="estimated_minutes">Minutes</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control border-0" id="estimated_seconds" name="estimated_seconds" placeholder="Seconds" required min="0" max="59" value="0">
                                        <label for="estimated_seconds">Seconds</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-flex align-items-center border-bottom pb-2">
                                <h5 class="text-secondary mb-0"><i class="ri-inventory-2-line me-2 text-primary"></i> Material Requisition</h5>
                                <span class="badge bg-soft-info text-info ms-2">Optional</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="productContainer">
                                <div class="row g-2 mb-3 product-row align-items-center">
                                    <div class="col-md-7">
                                        <select name="products[]" class="form-select border-2">
                                            <option value="">-- Select Material / Item --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit ?? 'qty' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="product_quantities[]" class="form-control border-2" placeholder="Quantity" min="1">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-soft-danger remove-product w-100"><i class="ri-close-line"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addProduct" class="btn btn-soft-info btn-sm">
                                <i class="ri-add-circle-line me-1"></i> Add Another Item
                            </button>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="p-3 bg-light rounded text-center">
                                <button type="submit" class="btn btn-primary px-5 py-2 fs-5 d-inline-flex align-items-center shadow">
                                    <i class="ri-send-plane-fill me-2"></i> Launch Production
                                </button>
                                <p class="text-muted small mt-2 mb-0">Project will be visible to assigned team members immediately.</p>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer p-0 border-0 overflow-hidden">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" class="img-fluid w-100" style="height: 12px; object-fit: cover; opacity: 0.5;">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Custom Dropdown */
.dropdown-menu-custom {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    background: #fff;
    max-height: 250px;
    overflow-y: auto;
}
.dropdown-menu-custom.show { display: block; }
.hover-bg-light:hover { background-color: #f8f9fa; }
.btn-soft-primary { background-color: rgba(43, 166, 219, 0.1); color: #2ba6db; border: none; }
.btn-soft-info { background-color: rgba(29, 174, 236, 0.1); color: #1daeec; border: none; }
.btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
.border-2 { border-width: 2px !important; }
.form-label { font-size: 0.9rem; color: #495057; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Dropdown Logic
    const employeeToggle = document.getElementById('employeeToggle');
    const employeeMenu = document.getElementById('employeeMenu');
    const toggleText = document.getElementById('toggleText');
    const selectedContainer = document.getElementById('selectedEmployees');

    employeeToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        employeeMenu.classList.toggle('show');
    });

    document.addEventListener('click', () => employeeMenu.classList.remove('show'));
    employeeMenu.addEventListener('click', (e) => e.stopPropagation());

    document.querySelectorAll('.employee-checkbox').forEach(box => {
        box.addEventListener('change', () => {
            const checked = Array.from(document.querySelectorAll('.employee-checkbox:checked'));
            selectedContainer.innerHTML = '';
            
            if(checked.length === 0) {
                toggleText.innerText = 'Select Staff...';
            } else {
                toggleText.innerText = `${checked.length} selected`;
                checked.forEach(cb => {
                    const span = document.createElement('span');
                    span.className = 'badge bg-primary px-2 py-1';
                    span.innerText = cb.value;
                    selectedContainer.appendChild(span);
                });
            }
        });
    });

    // Dynamic Fields Logic
    document.getElementById("add-size").addEventListener("click", function () {
        const container = document.getElementById("size-fields");
        const div = document.createElement("div");
        div.className = "d-flex mb-2 gap-2 align-items-center";
        div.innerHTML = `
            <input type="text" name="sizes[]" placeholder="e.g. 2M x 1M" class="form-control border-2" required>
            <input type="number" class="form-control border-2" name="quantities[]" placeholder="Qty" style="width: 120px;" required>
            <button type="button" class="btn btn-outline-danger btn-icon remove-size"><i class="ri-delete-bin-line"></i></button>
        `;
        container.appendChild(div);
    });

    document.getElementById("addProduct").addEventListener("click", function () {
        const container = document.getElementById("productContainer");
        const div = document.createElement("div");
        div.className = "row g-2 mb-3 product-row align-items-center";
        div.innerHTML = `
            <div class="col-md-7">
                <select name="products[]" class="form-select border-2">
                    <option value="">-- Select Material / Item --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="product_quantities[]" class="form-control border-2" placeholder="Quantity" min="1">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-soft-danger remove-product w-100"><i class="ri-close-line"></i></button>
            </div>
        `;
        container.appendChild(div);
    });

    document.addEventListener("click", function (e) {
        if (e.target.closest(".remove-size")) e.target.closest(".d-flex").remove();
        if (e.target.closest(".remove-product")) e.target.closest(".product-row").remove();
    });
});
</script>
@endsection