@extends('partials.layouts.master')

@section('title', 'Rideve Connect')
@section('title-sub', 'Administration')
@section('pagetitle', 'Asset Agreement')

@section('css')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/nouislider/nouislider.min.css') }}">
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

<style>
    .agreement-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .label-text {
        font-weight: 600;
        color: #495057;
        width: 150px;
        display: inline-block;
    }
    .value-text {
        color: #212529;
        border-bottom: 1px solid #e9ecef;
        display: inline-block;
        width: calc(100% - 160px);
    }
    #signature-image {
        border-bottom: 2px solid #333;
        padding-bottom: 5px;
        max-width: 250px;
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
                
                <div class="card agreement-card" id="printable-agreement">
                    <div class="card-header p-0" style="border:none; height: 15px;">
                        <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                    </div>

                    

                    <div class="card-body p-5">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <img style="height: 180px; width:auto;" src="{{ asset('assets/2.png') }}" alt="Rideve Logo" style="max-height: 80px;">
                            <div class="text-end">
                                <h2 class="text-primary fw-bold mb-0">ASSET</h2>
                                <h3 class="text-dark fw-light mt-0">AGREEMENT</h3>
                            </div>
                        </div>

                        <div class="alert alert-soft-info border-0 mb-4">
                            <p class="mb-0 small">
                                This document serves as a binding agreement between <strong>Rideve Media</strong> and the undersigned employee. 
                                By signing, the employee acknowledges the receipt of company property and assumes responsibility for its professional care and maintenance.
                            </p>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <h6 class="text-primary text-uppercase fw-semibold mb-3">1. Equipment Specifications</h6>
                                <hr class="mt-1">
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Asset Name:</span> <span class="value-text">{{ $asset->asset_name }}</span></p>
                                <p><span class="label-text">Asset ID:</span> <span class="value-text">{{ $asset->asset_number }}</span></p>
                                <p><span class="label-text">Type:</span> <span class="value-text">{{ $asset->asset_type }}</span></p>
                            </div>
                            
                            <div class="col-md-6">
                                <p><span class="label-text">Condition:</span> <span class="value-text text-success fw-bold">{{ $asset->condition }}</span></p>
                                <p><span class="label-text">Warranty:</span> <span class="value-text">{{ $asset->warranty_expiry }}</span></p>
                                <p><span class="label-text">Total Cost:</span> <span class="value-text">ZMW {{ number_format($asset->asset_cost, 2) }}</span></p>
                            </div>

                            <div class="col-12">
                                <p><span class="label-text">Description:</span> <span class="value-text w-100 d-block mt-1">{{ $asset->description }}</span></p>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <h6 class="text-primary text-uppercase fw-semibold mb-3">2. Employee Acknowledgment</h6>
                                <hr class="mt-1">
                            </div>
                            <div class="col-12">
                                <p class="lh-lg">
                                    I, <strong class="text-dark">{{ $asset->assigned_to }}</strong>, hereby confirm that I have received the asset(s) listed above in 
                                    <span class="text-decoration-underline">{{ strtolower($asset->condition) }}</span> condition. I agree to use this equipment solely for company business 
                                    and understand that any damage resulting from negligence may lead to personal liability.
                                </p>
                            </div>
                        </div>

                        <div class="row mt-5 pt-4">
                            <div class="col-6">
                                <div class="text-center px-4">
                                    <p class="mb-4 small text-muted">Authorized Issuer</p>
                                    <p class="fw-bold mb-0">{{ $asset->assigned_by }}</p>
                                    <div style="border-top: 1px solid #333; margin-top: 10px;"></div>
                                    <p class="small">Rideve Administration</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center px-4">
                                    <p class="mb-2 small text-muted">Employee Signature</p>
                                    @if($asset->signature)
                                        <img src="{{ $asset->signature }}" id="signature-image" alt="Signature">
                                    @else
                                        <div class="bg-light py-4 text-muted small">No Signature Recorded</div>
                                    @endif
                                    <p class="fw-bold mt-2 mb-0">{{ $asset->assigned_to }}</p>
                                    <p class="small">Date: {{ date('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="d-flex justify-content-center gap-3 mt-4 mb-5 no-print">
                    <a href="{{ route('ManageAssets') }}" class="btn btn-light px-4">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                    <button class="btn btn-secondary px-4" onclick="window.print()">
                        <i class="ri-printer-line align-middle me-1"></i> Print Agreement
                    </a>
            
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