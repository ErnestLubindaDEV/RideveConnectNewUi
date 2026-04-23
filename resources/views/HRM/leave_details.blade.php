@extends('partials.layouts.master')

@section('title', 'Rideve Connect')
@section('title-sub', 'Human Resources')
@section('pagetitle', 'Leave Application')

@section('css')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

<style>
    .agreement-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .label-text {
        font-weight: 600;
        color: #495057;
        width: 160px;
        display: inline-block;
    }
    .value-text {
        color: #212529;
        border-bottom: 1px solid #e9ecef;
        display: inline-block;
        width: calc(100% - 170px);
    }
    .signature-box {
        max-width: 200px;
        max-height: 80px;
        border-bottom: 1px solid #333;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
    }
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; }
        .app-wrapper { margin: 0 !important; padding: 0 !important; }
    }
</style>
@endsection

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                
                <div class="card agreement-card" id="printable-leave">
                    <div class="card-header p-0" style="border:none; height: 15px;">
                        <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                    </div>

                    <div class="card-body p-5">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <img style="height: 150px; width:auto;" src="{{ asset('assets/2.png') }}" alt="Rideve Logo">
                            <div class="text-end">
                                <h2 class="text-primary fw-bold mb-0">LEAVE</h2>
                                <h3 class="text-dark fw-light mt-0">APPLICATION</h3>
                                <div class="mt-2">
                                    <span class="status-badge bg-soft-{{    $leaveApplication->status == 'Rejected' ? 'danger' : 'success' }} text-{{    $leaveApplication->status == 'Rejected' ? 'danger' : 'success' }}">
                                        {{ strtoupper(   $leaveApplication->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <h6 class="text-primary text-uppercase fw-semibold mb-3">1. Employee Information</h6>
                                <hr class="mt-1">
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Full Name:</span> <span class="value-text">{{    $leaveApplication->full_name }}</span></p>
                                <p><span class="label-text">Employment Date:</span> <span class="value-text">{{ \Carbon\Carbon::parse(   $leaveApplication->employment_date)->format('d M, Y') }}</span></p>
                                <p><span class="label-text">Contact Number:</span> <span class="value-text">{{    $leaveApplication->phone_number }}</span></p>
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Contract Type:</span> <span class="value-text">{{    $leaveApplication->contract_type }}</span></p>
                                <p><span class="label-text">Emergency Contact:</span> <span class="value-text">{{    $leaveApplication->emergency_contact }}</span></p>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <h6 class="text-primary text-uppercase fw-semibold mb-3">2. Leave Specifics</h6>
                                <hr class="mt-1">
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Leave Type:</span> <span class="value-text fw-bold">{{    $leaveApplication->leave_type }}</span></p>
                                <p><span class="label-text">From Date:</span> <span class="value-text">{{ \Carbon\Carbon::parse(   $leaveApplication->leave_from)->format('d M, Y') }}</span></p>
                                <p><span class="label-text">To Date:</span> <span class="value-text">{{ \Carbon\Carbon::parse(   $leaveApplication->leave_to)->format('d M, Y') }}</span></p>
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Duration:</span> <span class="value-text">{{    $leaveApplication->leave_duration }} Days</span></p>
                                <p><span class="label-text">Supervisor:</span> <span class="value-text">{{    $leaveApplication->supervisor_name ?? 'N/A' }}</span></p>
                            </div>

                            <div class="col-12">
                                <p><span class="label-text">Additional Notes:</span> <span class="value-text w-100 d-block mt-1">{{    $leaveApplication->additional_notes ?? 'No additional comments provided.' }}</span></p>
                            </div>
                        </div>

                        <div class="row mt-5 pt-4 text-center">
                            <div class="col-4">
                                <p class="mb-2 small text-muted">Employee Signature</p>
                                @if(   $leaveApplication->employee_signature)
                                    <img src="{{    $leaveApplication->employee_signature }}" class="signature-box mb-2" alt="Employee Signature">
                                @else
                                    <div class="bg-light py-3 mb-2 text-muted small">Pending Signature</div>
                                @endif
                                <p class="fw-bold mb-0">{{    $leaveApplication->full_name }}</p>
                            </div>

                            <div class="col-4">
                                <p class="mb-2 small text-muted">Supervisor Approval</p>
                                @if(   $leaveApplication->supervisor_signature)
                                    <img src="{{    $leaveApplication->supervisor_signature }}" class="signature-box mb-2" alt="Supervisor Signature">
                                @else
                                    <div class="bg-light py-3 mb-2 text-muted small">Pending Approval</div>
                                @endif
                                <p class="fw-bold mb-0">{{    $leaveApplication->supervisor_name ?? 'Line Manager' }}</p>
                            </div>

                            <div class="col-4">
                                <p class="mb-2 small text-muted">HR / Administration</p>
                                @if(   $leaveApplication->hr_signature)
                                    <img src="{{    $leaveApplication->hr_signature }}" class="signature-box mb-2" alt="HR Signature">
                                @else
                                    <div class="bg-light py-3 mb-2 text-muted small">Pending Verification</div>
                                @endif
                                <p class="fw-bold mb-0">HR Department</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-4 mb-5 no-print">
                        <a href="{{ route('leave.index', ['employee_id' =>    $leaveApplication->id]) }}" class="btn btn-light px-4">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back
                        </a>
                        <button class="btn btn-secondary px-4" onclick="window.print()">
                            <i class="ri-printer-line align-middle me-1"></i> Print Application
                        </button>
                    </div>

                    <div class="card-footer p-0 mt-4" style="border:none; height: 15px;">
                        <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection