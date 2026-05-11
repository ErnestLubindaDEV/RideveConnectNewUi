@extends('partials.Layouts.master')

@section('title', 'Rideve Connect')
@section('title-sub', 'Human Resource Management')
@section('pagetitle', 'Apply for Leave')

@section('css')
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>

    /* Force the wrapper to have a height so the canvas doesn't collapse to 0 */
    .signature-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border: 2px solid #333;
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    #signature-pad { 
        display: block;
        width: 100% !important;
        height: 200px !important;
        cursor: crosshair; 
        touch-action: none; 
    }
    
    .border-thick { border: 2px solid #333 !important; }
    .btn-rideve { background-color: #1daeec; color: white; border: none; font-weight: 600; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #444; }
</style>
@endsection

@section('content')
<div class="container-fluid mb-5">
    {{-- Back Button --}}
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('HRM') }}" class="btn btn-primary d-flex align-items-center shadow-sm" 
           style="background-color:#ccdb2d; border-color: #ccdb2d; color: #1a1a1a; font-weight: 600;">
            <i class="ri-arrow-left-s-line fs-18 me-1"></i> Back
        </a>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card card-branded shadow-lg">
                <div class="p-0" style="height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" class="w-100 h-100" style="object-fit: cover;">
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center mb-5">
                        <div class="col-md-6">
                            <img src="{{ asset('assets/Logos/2.png') }}" alt="Logo" style="max-width: 260px; height:auto;">
                        </div>
                        <div class="col-md-6 text-md-end mt-4 mt-md-0">
                            <h2 class="fw-bold mb-0" style="color:#2ba6db;">Leave Application<br><span class="text-dark opacity-75">Form</span></h2>
                        </div>
                    </div>

                    <form action="{{ route('submitLeaveApplication') }}" method="POST" id="leaveForm" class="row g-4 mt-2">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">Employee Full Name</label>
                            <input type="text" class="form-control border-thick" name="full_name" value="{{ old('full_name', $employee->full_name ?? '') }}" required readonly >
                        </div>
 
                        <div class="col-md-6">
                            <label class="form-label">Date of Employment</label>
                            <input type="date" class="form-control border-thick bg-light" name="employment_date" value="{{ old('employment_date', $employee->start_date ?? '') }}" required readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contract Category</label>
                            <select name="contract_type" class="form-select border-thick">
                                <option value="Probational" {{ (old('contract_type', $employee->contract_type ?? '') == 'Probational') ? 'selected' : '' }}>Probational</option>
                                <option value="Intern" {{ (old('contract_type', $employee->contract_type ?? '') == 'Intern') ? 'selected' : '' }}>Intern</option>
                                <option value="Permanent" {{ (old('contract_type', $employee->contract_type ?? '') == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control border-thick bg-light" name="phone_number" value="{{ old('phone_number', $employee->phone_number ?? '') }}" required readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" class="form-control border-thick" name="emergency_contact" value="{{ old('emergency_contact', $employee->emergency_contact ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Leave Classification</label>
                            <select name="leave_type" class="form-select border-thick" required>
                                @foreach(['Sick', 'Annual', 'Local', 'Study', 'Special', 'Maternity', 'Unpaid', 'Compassionate', 'Commutation'] as $type)
                                    <option value="{{ $type }}" {{ (old('leave_type', $employee->leave_type ?? '') == $type) ? 'selected' : '' }}>{{ $type }} Leave</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" class="form-control border-thick" name="leave_duration" value="{{ old('leave_duration', $employee->leave_duration ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Leave From</label>
                            <input type="date" class="form-control border-thick" name="leave_from" value="{{ old('leave_from', $employee->leave_from ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Leave To</label>
                            <input type="date" class="form-control border-thick" name="leave_to" value="{{ old('leave_to', $employee->leave_to ?? '') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Justification (Optional)</label>
                            <textarea class="form-control border-thick" name="additional_notes" rows="3">{{ old('additional_notes', $employee->additional_notes ?? '') }}</textarea>
                        </div>

                      <div class="col-12 mt-4">
    <div class="p-4 bg-light rounded-3 border">
        <label class="form-label fw-bold d-block text-center mb-3">Employee Digital Signature</label>
        <div class="text-center">
            <div class="signature-container">
                <canvas id="signature-pad"></canvas>
            </div>
            <input type="hidden" name="signature" id="signature-input">
            <button type="button" class="btn btn-sm btn-link text-danger mt-2" id="clear-signature">
                <i class="ri-delete-bin-line"></i> Clear Signature
            </button>
        </div>
    </div>
</div>
                        <div class="col-12 mt-5 text-center">
                            <button type="submit" class="btn btn-rideve btn-lg px-5 py-3 shadow-lg">
                                Confirm & Submit Leave
                            </button>
                        </div>
                    </form>
                </div>
                <div class="p-0" style="height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" class="w-100 h-100" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        const signatureInput = document.getElementById('signature-input');
        const clearBtn = document.getElementById('clear-signature');
        const form = document.getElementById('leaveForm');

        if (!canvas) return;

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Required by the library after resize
        }

        window.addEventListener("resize", resizeCanvas);
        // Small delay to ensure the container has settled its width
        setTimeout(resizeCanvas, 200);

        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            signaturePad.clear();
            signatureInput.value = '';
        });

        form.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                alert("Please provide a digital signature.");
                e.preventDefault();
            } else {
                // Transfer the drawing to the hidden input
                signatureInput.value = signaturePad.toDataURL();
            }
        });
    });
</script>
@endsection