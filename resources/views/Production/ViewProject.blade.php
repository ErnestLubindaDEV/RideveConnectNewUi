@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('css')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --rideve-blue: #2ba6db;
        --rideve-dark: #333;
    }

    body { font-family: 'Montserrat', sans-serif; background-color: #f4f7f6; }
    
    .approval-container {
        background: white;
        padding: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 8px;
        border-top: 15px solid var(--rideve-blue);
        position: relative;
    }

    .form-header-img {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        height: 8px;
        width: 100%;
        object-fit: cover;
    }

    .checklist-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        border-left: 5px solid var(--rideve-blue);
        margin-bottom: 20px;
    }

    /* Modern Checkbox Styling */
    .custom-checkbox {
        width: 28px;
        height: 28px;
        cursor: pointer;
        accent-color: var(--rideve-blue);
        border: 2px solid var(--rideve-dark);
        border-radius: 4px;
    }

    .checklist-label {
        font-size: 15px;
        font-weight: 500;
        color: #444;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .checklist-label strong { color: #000; }

    .artwork-preview-frame {
        border: 8px solid #f0f0f0;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin: 40px 0;
        background: #fff;
        padding: 10px;
    }

    .section-title {
        font-weight: 700;
        color: var(--rideve-dark);
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
        text-transform: uppercase;
        font-size: 1.2rem;
    }

    .signature-area {
        background: #fff;
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 15px;
        display: inline-block;
    }

    canvas#signature-pad {
        background-color: #fff;
        cursor: crosshair;
    }

    .btn-approve {
        background-color: var(--rideve-blue);
        border: none;
        padding: 15px 40px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-approve:hover {
        background-color: #1a8fbf;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')

<div class="container py-5">
    <div class="approval-container mx-auto max-w-1000">
        <img src="{{ asset('assets/BG-02.jpeg') }}" class="form-header-img">

        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="/assets/Logos/2.png" alt="Rideve Logo" style="max-width: 280px;">
            </div>
            <div class="col-md-6 text-end">
                <h1 style="color: var(--rideve-blue); font-weight: 800; line-height: 1;">
                    ARTWORK<br><span style="color: #333; font-size: 0.8em;">APPROVAL FORM</span>
                </h1>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm p-4 mb-5">
            <h5 class="fw-bold text-dark"><i class="ri-error-warning-fill me-2"></i>PLEASE CHECK PROOF VERY CAREFULLY</h5>
            <p class="mb-0 small text-dark opacity-75">
                Every care has been taken to follow your instructions, but the final responsibility for accuracy rests with you, the client. Please check the proof thoroughly as liability by Rideve Media LTD is limited to corrections only.
            </p>
        </div>

        @if(isset($project->artwork))
            <div class="artwork-preview-frame text-center">
                <h6 class="text-muted mb-3"><i class="ri-eye-line me-1"></i> Final Artwork Preview</h6>
                <img src="{{ asset('storage/' . $project->artwork) }}" alt="Artwork Preview" class="img-fluid rounded border">
            </div>
        @endif

        <form action="{{ route('approval.store', ['project_id' => $project->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="section-title"><i class="ri-checkbox-multiple-line me-2"></i>Quality Assurance Checklist</div>
            <div class="checklist-card">
                <ul class="list-unstyled mb-0">
                    @php
                        $items = [
                            ['val' => 'Correct Fonts Used', 'label' => 'Correct Fonts were used / All text is spaced properly / All text is formatted and aligned correctly'],
                            ['val' => 'All images and colors correct', 'label' => '<strong>All images are correct / Colours are correct</strong>'],
                            ['val' => 'All important information included', 'label' => 'All important information is included - who, what, where, when, and why'],
                            ['val' => 'All wording phrasing correct', 'label' => '<strong>All wording / Phrasing is typed correctly</strong>'],
                            ['val' => 'Spelling and grammar correct', 'label' => 'All spelling and grammar is correct'],
                            ['val' => 'Spelling of names and places correct', 'label' => '<strong>Spelling of any names & places is correct</strong>'],
                            ['val' => 'All contact numbers correct', 'label' => 'All contact numbers (Phone, fax, mobile) are correct'],
                            ['val' => 'Physical email and web addresses correct', 'label' => '<strong>Physical, email, and web addresses are correct</strong>'],
                            ['val' => 'Stated dimensions of images correct', 'label' => 'Stated dimensions of images are correct']
                        ];
                    @endphp

                    @foreach($items as $item)
                    <li class="mb-3">
                        <label class="checklist-label">
                            <input type="checkbox" name="checklist[]" value="{{ $item['val'] }}" class="custom-checkbox">
                            <span>{!! $item['label'] !!}</span>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="section-title mt-5"><i class="ri-palette-line me-2"></i>Colour Reproduction Waiver</div>
            <div class="p-3 bg-light rounded mb-4">
                <p class="small text-muted italic">
                    Since all computer monitors display colour differently, we cannot guarantee print colours will match colours viewed on screen. We do not accept returns based on colour inconsistencies.
                </p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="confirmation" required id="confirmWaiver">
                    <label class="form-check-label small fw-bold" for="confirmWaiver">
                        I confirm that I have read and understood the colour reproduction waiver.
                    </label>
                </div>
            </div>

            <div class="section-title mt-5"><i class="ri-question-answer-line me-2"></i>Project Status</div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check custom-radio-box p-3 border rounded bg-white shadow-sm" style="flex: 1; min-width: 250px;">
                            <input class="form-check-input" type="radio" name="proof_status" value="Okay to Finalise Images with no changes" id="status1" required>
                            <label class="form-check-label fw-bold" for="status1">Ready (No Changes)</label>
                        </div>
                        <div class="form-check custom-radio-box p-3 border rounded bg-white shadow-sm" style="flex: 1; min-width: 250px;">
                            <input class="form-check-input" type="radio" name="proof_status" value="Okay to Finalise Images with the changes described below" id="status2">
                            <label class="form-check-label fw-bold" for="status2">Ready (With Minor Changes)</label>
                        </div>
                        <div class="form-check custom-radio-box p-3 border rounded bg-white shadow-sm" style="flex: 1; min-width: 250px;">
                            <input class="form-check-input" type="radio" name="proof_status" value="Not ready to finalise images" id="status3">
                            <label class="form-check-label fw-bold text-danger" for="status3">Not Ready (Revision Required)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-title"><i class="ri-quill-pen-line me-2"></i>Final Authorization</div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Client Name</label>
                    <input type="text" name="client_name" class="form-control border-2" value="{{ $project->client_name }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Approval Date</label>
                    <input type="date" name="approval_date" class="form-control border-2" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="text-center p-4 bg-light rounded">
                <p class="small mb-3 fw-bold">Please sign inside the box below:</p>
                <div class="signature-area shadow-sm">
                    <canvas id="signature-pad" width="600" height="150" class="rounded"></canvas>
                </div>
                <div class="mt-2">
                    <button type="button" id="clear-signature" class="btn btn-link btn-sm text-danger"><i class="ri-refresh-line"></i> Clear Signature</button>
                    <input type="hidden" id="signature" name="signature" required>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-approve text-white shadow-lg">
                    SUBMIT FINAL APPROVAL
                </button>
                <div class="mt-3">
                    <a href="{{ route('projects.manage') }}" class="text-muted text-decoration-none small">
                        <i class="ri-arrow-left-line"></i> Return to Project List
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    document.getElementById('clear-signature').addEventListener('click', () => signaturePad.clear());

    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert('Your signature is required for authorization.');
        } else {
            document.getElementById('signature').value = signaturePad.toDataURL();
        }
    });
});
</script>
@endsection