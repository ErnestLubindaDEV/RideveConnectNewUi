@extends('partials.Layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Add Asset Form')
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

    .signature-wrapper {
        border: 2px solid #333;
        background-color: #fff;
        border-radius: 4px;
        position: relative;
        width: 100%;
        max-width: 500px;
        height: 150px;
    }
    #signature-pad { width: 100%; height: 100%; cursor: crosshair; touch-action: none; }
</style>
@endsection

@section('content')

<div class="container-fluid mb-5">
    {{-- Top Action Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-muted fw-light">Reviewing Leave Request #{{ $leave->id }}</h4>
        <a href="{{ route('HRM') }}" class="btn btn-outline-dark d-flex align-items-center" style="background-color:#ccdb2d; border:none; color:#1a1a1a; font-weight:600;">
            <i class="ri-arrow-left-line me-1"></i> Back to Dashboard
        </a>
    </div>

    @if ($errors->any() || session('success'))
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card approval-card shadow-lg">
                <img src="/assets/BG-02.jpeg" class="branding-strip">
                
                <div class="card-body p-4 p-md-5">
                    {{-- Header Section --}}
                    <div class="d-flex justify-content-between align-items-start mb-5">
                        <img src="/assets/Logos/2.png" alt="Logo" style="max-width: 220px;">
                        <div class="text-end">
                            <h2 class="fw-bold mb-0" style="color:#2ba6db;">Leave Approval</h2>
                            <p class="text-muted small">Supervisor/Manager Review</p>
                        </div>
                    </div>

                    <form action="{{ route('leave.approve', $leave->id) }}" method="POST" id="approvalForm">
                        @csrf
                        <div class="row g-4">
                            {{-- Read-Only Employee Details --}}
                            <div class="col-md-12">
                                <label class="form-label">Employee Name</label>
                                <input type="text" class="form-control readonly-input" value="{{ $leave->employee->full_name }}" readonly>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Leave Classification</label>
                                <input type="text" class="form-control readonly-input" value="{{ $leave->leave_type }} Leave" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Commencement Date</label>
                                <input type="text" class="form-control readonly-input" value="{{ $leave->leave_from }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Resumption Date</label>
                                <input type="text" class="form-control readonly-input" value="{{ $leave->leave_to }}" readonly>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Employee's Justification</label>
                                <textarea class="form-control readonly-input" rows="2" readonly>{{ $leave->additional_notes }}</textarea>
                            </div>

                            <hr class="my-4">

                            {{-- Decision Section --}}
                            <div class="col-md-12">
                                <label class="form-label fs-15 text-primary">Your Final Decision</label>
                                <select name="approval_status" class="form-select border-primary fw-bold" required>
                                    <option value="approved" {{ $leave->status == 'Supervisor approved' ? 'selected' : '' }}>Approve Request</option>
                                    <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>Reject Request</option>
                                </select>
                            </div>

                            {{-- Signature Area --}}
                            <div class="col-md-12 mt-4">
                                <label class="form-label d-block">Authorized Digital Signature</label>
                                <div class="signature-wrapper">
                                    <canvas id="signature-pad"></canvas>
                                </div>
                                <div class="mt-2">
                                    <button type="button" id="clear-signature" class="btn btn-sm btn-link text-danger p-0">
                                        <i class="ri-delete-bin-line"></i> Clear & Re-sign
                                    </button>
                                </div>
                                <input type="hidden" id="signature-input" name="signature">
                            </div>

                            <div class="col-md-12 mt-5 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-lg" style="background-color:#1daeec; border:none; font-weight:600;">
                                    Complete Approval Process
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <img src="/assets/BG-02.jpeg" class="branding-strip">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const clearButton = document.getElementById('clear-signature');
    const signatureInput = document.getElementById('signature-input');
    const form = document.getElementById('approvalForm');

    // Initialize Signature Pad
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    // Handle Canvas Sizing
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }

    window.addEventListener("resize", resizeCanvas);
    setTimeout(resizeCanvas, 200);

    // Clear Button
    clearButton.addEventListener('click', () => {
        signaturePad.clear();
        signatureInput.value = '';
    });

    // Submit Logic
    form.addEventListener('submit', (e) => {
        if (signaturePad.isEmpty()) {
            alert("Please provide your authorized signature.");
            e.preventDefault();
        } else {
            signatureInput.value = signaturePad.toDataURL();
        }
    });

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(a => {
            a.style.transition = "opacity 0.5s ease";
            a.style.opacity = "0";
            setTimeout(() => a.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection