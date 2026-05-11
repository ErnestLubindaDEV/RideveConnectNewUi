@extends('partials.Layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Add Asset Form')
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
    <style>
        #signature-pad {
            border: 1px dashed #ced4da;
            border-radius: 4px;
            background-color: #f8f9fa;
            cursor: crosshair;
        }
        .form-label { font-weight: 500; }
    </style>
@endsection

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                
               
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                       <div class="card-header p-0"  style=" border:none;  height: 20px; ">
                <img  src="../assets/BG-02.jpeg" style=" border: none; max-width: auto; height: 15px;   padding-left:0px" alt="Pattern" class="img-fluid w-100">
            </div>
                    <div class="card-body p-4">
                         <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-0">Add New Asset</h4>
                        <p class="text-muted">Register a new company asset and assign it to an employee.</p>
                    </div>
                   
                </div>

                        <form action="{{ route('storeAsset') }}" method="POST" id="assetForm">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="text-primary text-uppercase fw-semibold mb-3">Basic Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="asset_name" name="asset_name" placeholder="e.g. MacBook Pro M3" value="{{ old('asset_name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="serial_number" class="form-label">Serial / License Number</label>
                                    <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="SN-12345678" value="{{ old('serial_number') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="asset_type" class="form-label">Asset Type</label>
                                    <select id="asset_type" name="asset_type" class="form-select">
                                        <option value="Electronics" {{ old('asset_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                        <option value="Furniture" {{ old('asset_type') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                        <option value="Vehicle" {{ old('asset_type') == 'Vehicle' ? 'selected' : '' }}>Vehicle</option>
                                        <option value="Office Supplies" {{ old('asset_type') == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                                        <option value="Other" {{ old('asset_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select id="condition" name="condition" class="form-select">
                                        <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Used" {{ old('condition') == 'Used' ? 'selected' : '' }}>Used</option>
                                        <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="asset_cost" class="form-label">Asset Cost (ZMW)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">K</span>
                                        <input type="number" step="0.01" class="form-control" id="asset_cost" name="asset_cost" placeholder="0.00" value="{{ old('asset_cost') }}">
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <h6 class="text-primary text-uppercase fw-semibold mb-3">Assignment & Dates</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="assigned_to" class="form-label">Assign To Employee</label>
                                    <select id="assigned_to" name="assigned_to" class="form-select">
                                        <option value="Unassigned">Leave Unassigned</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->full_name }}" {{ old('assigned_to') == $employee->full_name ? 'selected' : '' }}>
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="assigned_by" class="form-label">Assigned By</label>
                                    <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                                    <input type="hidden" name="assigned_by" value="{{ Auth::user()->name }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="collection_date" class="form-label">Collection Date</label>
                                    <input type="date" class="form-control" id="collection_date" name="collection_date" value="{{ old('collection_date') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control" id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Description / Notes</label>
                                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="Enter additional asset details...">{{ old('description') }}</textarea>
                                </div>

                                <div id="signature-section" class="col-12 mt-3" style="display: none;">
                                    <label class="form-label d-block text-danger">Employee Acceptance Signature</label>
                                    <canvas id="signature-pad" width="600" height="150" class="w-100"></canvas>
                                    <input type="hidden" id="signature" name="signature">
                                    <div class="mt-2 text-end">
                                        <button type="button" id="clear-signature" class="btn btn-sm btn-soft-danger">Clear Signature</button>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg w-50">Save Asset Record</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assignedTo = document.getElementById('assigned_to');
        const sigSection = document.getElementById('signature-section');
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        const signatureInput = document.getElementById('signature');
        const clearBtn = document.getElementById('clear-signature');
        
        let drawing = false;

        // Toggle signature pad based on assignment
        function toggleSignature() {
            if (assignedTo.value !== "Unassigned" && assignedTo.value !== "") {
                sigSection.style.display = 'block';
            } else {
                sigSection.style.display = 'none';
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                signatureInput.value = '';
            }
        }

        assignedTo.addEventListener('change', toggleSignature);
        toggleSignature(); // Initial check

        // Signature Logic
        function getMousePos(canvasDom, touchOrMouseEvent) {
            let rect = canvasDom.getBoundingClientRect();
            let clientX = touchOrMouseEvent.touches ? touchOrMouseEvent.touches[0].clientX : touchOrMouseEvent.clientX;
            let clientY = touchOrMouseEvent.touches ? touchOrMouseEvent.touches[0].clientY : touchOrMouseEvent.clientY;
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        canvas.addEventListener("mousedown", (e) => {
            drawing = true;
            let pos = getMousePos(canvas, e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        });

        canvas.addEventListener("mousemove", (e) => {
            if (!drawing) return;
            let pos = getMousePos(canvas, e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
        });

        canvas.addEventListener("mouseup", () => {
            drawing = false;
            signatureInput.value = canvas.toDataURL();
        });

        clearBtn.addEventListener("click", () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
        });

        // Touch support for mobile devices
        canvas.addEventListener("touchstart", (e) => { e.preventDefault(); drawing = true; let pos = getMousePos(canvas, e); ctx.beginPath(); ctx.moveTo(pos.x, pos.y); }, false);
        canvas.addEventListener("touchmove", (e) => { e.preventDefault(); if (!drawing) return; let pos = getMousePos(canvas, e); ctx.lineTo(pos.x, pos.y); ctx.stroke(); }, false);
        canvas.addEventListener("touchend", (e) => { drawing = false; signatureInput.value = canvas.toDataURL(); }, false);
    });
</script>
@endsection