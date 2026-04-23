@extends('partials.layouts.master')

@section('title', 'Rideve Connect - Fleet Management')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
@endsection

@section('content')

{{-- Feedback Alerts --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header p-0" style="border:none; height: 15px;">
        <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
    </div>

    <div class="card-body">
        <ul class="nav nav-pills arrow-pills nav-justified nav-danger" role="tablist">
         <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#inventory_tab" role="tab">
        <span><i class="ri-car-line"></i></span> <span>Vehicle Register</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#service_tab" role="tab">
        <span><i class="ri-calendar-todo-line"></i></span> <span>Service Schedule</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#repairs-history" role="tab">
        <span><i class="ri-tools-line"></i></span> <span>Repairs & Breakdowns</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#compliance_tab" role="tab">
        <span><i class="ri-shield-check-line"></i></span> <span>Compliance</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#fuel-logs" role="tab">
        <span><i class="ri-gas-station-line"></i></span> <span>Fuel Logs</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#accidents_tab" role="tab">
        <span><i class="ri-error-warning-line"></i></span> <span>Accident History</span>
    </a>
</li>
</ul>

        <div class="tab-content pt-3">
            <div class="tab-pane active show" id="inventory_tab" role="tabpanel">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                            <i class="fas fa-plus me-1"></i> Add Vehicle
                        </button>
                    </div>

                    <table id="fleetTable" class="table table-hover align-middle border">
                        <thead>
                            <tr>
                                <th>Number Plate</th>
                                <th>Make/Model</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Engine Number</th>
                                <th>Transmission</th>
                                <th>Mileage</th>
                                <th>Purchase Date</th>
                                <th>Assigned Driver</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td><strong>{{ $vehicle->registration_number }}</strong></td>
                                    <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                                    <td>{{ $vehicle->assigned_driver ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge {{ $vehicle->status == 'Active' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $vehicle->status }}
                                        </span>
                                    </td>
                                    <td>{{ $vehicle->engine_type }}</td>
                                    <td>{{ $vehicle->transmission }}</td>
                                    <td>{{ $vehicle->current_mileage }}</td>
                                    <td>{{ $vehicle->purchase_date }}</td>
                                    <td>{{ $vehicle->assigned_driver }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editVehicleModal{{ $vehicle->id }}">
                                            <i class="fas fa-edit me-1"></i>
                                            <span>Edit Vehicle</span>
                                        </button>

                                        <form action="{{ route('fleet.destroy', $vehicle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this vehicle?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="service_tab" role="tabpanel">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                        <h4 class="card-title">Service Schedule</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="fas fa-plus me-1"></i> Update Service Record
                        </button>
                    </div>

                    <table id="serviceTable" class="table table-striped table-bordered w-100 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Vehicle</th>
                                <th>Provider</th>
                                <th>Last Service</th>
                                <th>Next Due</th>
                                <th>Cost</th>
                                <th>Remarks</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                                @php $schedule = $vehicle->serviceSchedule; @endphp
                                <tr>
                                    <td class="text-center">
                                        @php
                                            $statusClass = match($schedule?->service_status) {
                                                'up-to-date' => 'bg-success-subtle text-success',
                                                'pending'    => 'bg-warning-subtle text-warning',
                                                'overdue'    => 'bg-danger-subtle text-danger',
                                                default      => 'bg-light text-muted'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }} border px-2">
                                            {{ ucfirst($schedule?->service_status ?? 'No Data') }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $vehicle->registration_number }}</strong><br>
                                        <small class="text-muted">{{ $vehicle->make }} {{ $vehicle->model }}</small>
                                    </td>
                                    <td>{{ $schedule?->service_provider ?? 'Not Assigned' }}</td>
                                    <td>
                                        <small>
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $schedule?->last_service_date ? \Carbon\Carbon::parse($schedule->last_service_date)->format('d M, Y') : 'N/A' }}<br>
                                            <i class="fas fa-tachometer-alt me-1"></i>
                                            {{ number_format($schedule?->last_service_mileage ?? 0) }} km
                                        </small>
                                    </td>
                                    <td>
                                        <small class="fw-bold">
                                            <i class="far fa-calendar-check me-1"></i>
                                            {{ $schedule?->next_service_date ? \Carbon\Carbon::parse($schedule->next_service_date)->format('d M, Y') : 'TBD' }}<br>
                                            <i class="fas fa-arrow-circle-right me-1"></i>
                                            {{ number_format($schedule?->next_service_mileage ?? 0) }} km
                                        </small>
                                    </td>
                                    <td class="text-end">K{{ number_format($schedule?->estimated_cost ?? 0, 2) }}</td>
                                    <td><small class="text-muted">{{ Str::limit($schedule?->remarks, 20) }}</small></td>
                                    <td>{{ $schedule?->updated_at ? \Carbon\Carbon::parse($schedule->updated_at)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="repairs-history" role="tabpanel">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-5">
                                <div class="p-3 rounded-3 bg-light border-start border-danger border-5">
                                    <p class="small text-muted mb-1 uppercase fw-bold">Total Repair Spend ({{ now()->format('F') }})</p>
                                    <h3 class="fw-bold text-danger mb-0">K {{ number_format($monthlyRepairTotal, 2) }}</h3>
                                </div>
                            </div>
                            <div class="col-md-7 text-end">
                                <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addRepairModal">
                                    <i class="fas fa-plus-circle me-2"></i>Log New Breakdown
                                </button>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light text-uppercase small">
                                    <tr>
                                        <th>Date</th>
                                        <th>Vehicle</th>
                                        <th>Reported By</th>
                                        <th>Description</th>
                                        <th>Service Provider</th>
                                        <th>Cost (ZMW)</th>
                                        <th>Repair Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $groupedLogs = $repairLogs->groupBy(function($item) {
                                            return \Carbon\Carbon::parse($item->report_date)->format('F Y');
                                        });
                                    @endphp

                                    @forelse($groupedLogs as $month => $logs)
                                        <tr class="table-light">
                                            <td colspan="5" class="fw-bold text-dark">
                                                <i class="far fa-calendar-alt me-2 text-danger"></i> {{ $month }}
                                            </td>
                                            <td colspan="2" class="text-end fw-bold text-danger">
                                                <span class="text-muted small fw-normal">Monthly Total:</span>
                                                K {{ number_format($logs->sum('cost'), 2) }}
                                            </td>
                                        </tr>

                                        @foreach($logs as $log)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($log->report_date)->format('d M, Y') }}</td>
                                                <td><strong>{{ $log->vehicle->registration_number }}</strong></td>
                                                <td>{{ $log->reported_by }}</td>
                                                <td>{{ Str::limit($log->repair_description, 30) }}</td>
                                                <td>{{ $log->service_provider }}</td>
                                                <td class="fw-bold text-primary">K {{ number_format($log->cost, 2) }}</td>
                                                <td>
                                                    <select class="form-select form-select-sm status-updater"
                                                            data-id="{{ $log->id }}"
                                                            style="width: 130px;">
                                                        <option value="Not Started" {{ $log->downtime_status == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                                        <option value="Ongoing" {{ $log->downtime_status == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                        <option value="Repaired" {{ $log->downtime_status == 'Repaired' ? 'selected' : '' }}>Repaired</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-folder-open d-block mb-2 fs-2"></i>
                                                No repair records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

<div class="tab-pane fade" id="accidents_tab" role="tabpanel">
    <div class="table-responsive">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Incident & Accident History</h4>
            <button type="button" class="btn btn-danger btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addAccidentModal">
                <i class="fas fa-exclamation-triangle me-1"></i> Report Incident
            </button>
        </div>

        <table id="accidentTable" class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Number Plate</th>
                    <th>Driver</th>
                    <th>Location</th>
                    <th>Severity</th>
                    <th>Police Report</th>
                    <th>Est. Cost (ZMW)</th>
                    <th>Insurance</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accidents as $accident)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($accident->incident_date)->format('d M, Y') }}</td>
                        <td><strong>{{ $accident->vehicle_id }}</strong></td>
                        <td>{{ $accident->driver_name }}</td>
                        <td>{{ $accident->location }}</td>
                        <td>
                            <span class="badge {{ $accident->severity == 'Major' || $accident->severity == 'Totaled' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $accident->severity }}
                            </span>
                        </td>
                        <td><code class="text-dark">{{ $accident->police_report_number ?? 'N/A' }}</code></td>
                        <td>K {{ number_format($accident->estimated_repair_cost, 2) }}</td>
                        <td>
                            <span class="badge border {{ $accident->insurance_status == 'Claimed' ? 'bg-success-subtle text-success border-success' : 'bg-secondary-subtle text-secondary border-secondary' }}">
                                {{ $accident->insurance_status ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="text-center">
                           <button type="button" 
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editAccidentModal" 
                                    onclick="populateAccidentEditModal({{ json_encode($accident) }})">
                                <i class="ri-edit-line me-1"></i>
                                <span>Edit</span>
                            </button>          
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

 <div class="tab-pane fade" id="compliance_tab" role="tabpanel">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                       <div>
                                <h4 class="card-title mb-1">Compliance Monitoring</h4>
                                <div id="complianceExportButtons"></div> {{-- This is where the buttons will inject --}}
                            </div>
                            <button type="button" class="btn btn-dark btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addComplianceModal">
                            <i class="fas fa-plus me-1"></i> Add Compliance Record
                        </button>
                    </div>

                <table style="padding-top:-150px;" id="complianceTable" class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Registration</th>
                            <th>Insurance Provider</th>
                            <th>Policy Number</th>
                            <th>Insurance Expiry</th>
                            <th>Road Tax Expiry</th>
                            <th>Fitness Expiry</th>
                            <!-- <th class="text-center">Reminder</th> -->
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 
                        @foreach($compliances as $item)
                        <tr>
                            <td>
                                       @php
                            // Map the status to specific Bootstrap classes and icons
                            $statusConfig = match($item->calculated_status) {
                                'Valid'   => ['class' => 'bg-success-subtle text-success border-success', 'icon' => 'fa-check-circle'],
                                'Pending' => ['class' => 'bg-warning-subtle text-warning border-warning', 'icon' => 'fa-exclamation-circle'],
                                'Expired' => ['class' => 'bg-danger-subtle text-danger border-danger', 'icon' => 'fa-exclamation-triangle'],
                                default   => ['class' => 'bg-light text-muted border-secondary', 'icon' => 'fa-question-circle'],
                            };
                        @endphp
                              <span class="badge rounded-pill border px-2 {{ $statusConfig['class'] }}">
                                    <i class="fas {{ $statusConfig['icon'] }} me-1"></i>
                                    {{ $item->calculated_status }}
                            </span>
                            </td>
                            <td><strong>{{ $item->vehicle->registration_number ?? 'N/A' }}</strong></td>
                            <td>{{ $item->insurance_provider ?? 'N/A' }}</td>
                            <td><code class="text-dark">{{ $item->insurance_policy_number ?? 'N/A' }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($item->insurance_expiry_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->road_tax_expiry)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fitness_certificate_expiry)->format('d M, Y') }}</td>
                            <!-- <td class="text-center">
                                <span class="badge {{ $item->reminder_sent == 'YES' ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $item->reminder_sent }}
                                </span>
                            </td> -->
                            <td class="text-center">
                                <div class="btn-group">
                                  <button type="button" 
                                            class="btn btn-sm btn-outline-info d-inline-flex align-items-center" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editComplianceModal{{ $vehicle->id }}"
                                            title="Edit Compliance">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        <span class="small">Edit</span>
                                    </button>
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





<div class="tab-pane fade" id="fuel-logs" role="tabpanel">
    <div class="card border-0 shadow-sm mt-3">
            <div class="table-responsive">
                <table id="fuelTable" class="table table-hover align-middle">
                    <thead class="bg-light text-uppercase small fw-bold">
                        <tr>
                            <th>Date</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Station</th>
                            <th>Litres</th>
                            <th>Cost (ZMW)</th>
                            <th>Odometer</th>
                            <th>Efficiency</th>
                            <th class="no-export text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 1. CURRENT WEEK ACTIVE ENTRY SECTION --}}
                        @php
                            $currentWeekKey = now()->format('Y - \W\e\e\k W');
                        @endphp

                        <tr class="table-primary border-bottom border-primary">
                            <td colspan="9" class="fw-bold py-3">
                                <i class="fas fa-calendar-check me-2"></i> ACTIVE WEEK: {{ $currentWeekKey }} 
                                <span class="badge bg-white text-primary ms-2 shadow-sm">Reporting Period</span>
                            </td>
                        </tr>

                        @foreach($vehicles as $vehicle)
                            @php 
                                // Identify if a log exists for this specific vehicle in the current week
                                $log = $fuelLogs->where('vehicle_id', $vehicle->registration_number)
                                                ->filter(fn($l) => \Carbon\Carbon::parse($l->date)->format('Y - \W\e\e\k W') == $currentWeekKey)
                                                ->first();
                            @endphp
                            
                            <tr class="{{ $log ? '' : 'bg-light' }}">
                                <td class="small">{{ $log ? \Carbon\Carbon::parse($log->date)->format('d M') : '---' }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $vehicle->registration_number }}</span>
                                </td>
                                
                                @if($log)
                                    {{-- Display existing weekly data --}}
                                    <td>{{ $log->driver }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $log->fuel_station ?? 'Not Specified' }}</span></td>
                                    <td>{{ number_format($log->litres, 2) }} L</td>
                                    <td class="fw-bold text-success">K {{ number_format($log->cost, 2) }}</td>
                                    <td>{{ number_format($log->odometer_reading) }} <small class="text-muted">km</small></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            {{ number_format($log->km_per_litre, 2) }} km/l
                                        </span>
                                    </td>
                                    <td class="text-center">
                                         <button type="button" 
                                                class="btn btn-sm btn-primary d-inline-flex align-items-center" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFuelModal" 
                                                onclick="populateEditModal({{ json_encode($log) }})">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </button>
                                    </td>
                                @else
                                    {{-- Entry mode for missing data --}}
                                    <td colspan="6" class="text-center">
                                        <span class="text-muted small italic">
                                            <i class="fas fa-info-circle me-1"></i> No fuel record found for this week.
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success shadow-sm px-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#addFuelModal" 
                                                onclick="prepareFuelModal('{{ $vehicle->registration_number }}', '{{ $vehicle->assigned_driver ?? 'Unassigned' }}')">
                                            <i class="fas fa-plus-circle me-1"></i> Add
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        {{-- 2. HISTORICAL DATA SECTION --}}
                        @php
                            $previousLogs = $fuelLogs->filter(fn($l) => \Carbon\Carbon::parse($l->date)->format('Y - \W\e\e\k W') != $currentWeekKey)
                                                     ->groupBy(fn($l) => \Carbon\Carbon::parse($l->date)->format('Y - \W\e\e\k W'));
                        @endphp

                        @foreach($previousLogs as $week => $logs)
                            <tr class="table-secondary border-top">
                                <td colspan="4" class="fw-bold small"><i class="fas fa-history me-1"></i> {{ $week }}</td>
                                <td class="fw-bold small">{{ number_format($logs->sum('litres'), 2) }} L</td>
                                <td class="fw-bold small">K {{ number_format($logs->sum('cost'), 2) }}</td>
                                <td colspan="3" class="text-end small text-muted italic">Total Spend for Week</td>
                            </tr>
                            @foreach($logs as $history)
                                <tr class="small text-muted opacity-75">
                                    <td>{{ \Carbon\Carbon::parse($history->date)->format('d M') }}</td>
                                    <td>{{ $history->vehicle_id }}</td>
                                    <td>{{ $history->driver }}</td>
                                    <td>{{ $history->fuel_station }}</td>
                                    <td>{{ $history->litres }} L</td>
                                    <td>K {{ number_format($history->cost, 2) }}</td>
                                    <td>{{ number_format($history->odometer_reading) }}</td>
                                    <td>{{ number_format($history->km_per_litre, 2) }}</td>
                                    <td class="text-center">---</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addComplianceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('fleet.storeCompliance') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                         <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title">New Compliance Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vehicle</label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="" selected disabled>Select Vehicle...</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->registration_number }} ({{ $v->make }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Insurance Provider</label>
                            <input type="text" name="insurance_provider" class="form-control" placeholder="e.g. ZSIC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Policy Number</label>
                            <input type="text" name="insurance_policy_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Insurance Expiry</label>
                            <input type="date" name="insurance_expiry_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Road Tax Expiry</label>
                            <input type="date" name="road_tax_expiry" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fitness Expiry</label>
                            <input type="date" name="fitness_certificate_expiry" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Compliance Status</label>
                            <select name="compliance_status" class="form-select" required>
                                <option value="Valid">Valid</option>
                                <option value="Pending">Pending</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Send Reminder?</label>
                            <select name="reminder_sent" class="form-select" required>
                                <option value="NO">No</option>
                                <option value="YES">Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Compliance Record</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="addFuelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="card-header p-0" style="border:none; height: 10px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 10px;" class="img-fluid w-100">
            </div>
            <form action="{{ route('fuel.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-gas-pump me-2"></i>Record Refuel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                   <div class="col-12">
                        <label class="form-label fw-bold">Vehicle Registration</label>
                        <input type="text" name="vehicle_id" id="modal_vehicle_display" class="form-control bg-light" readonly required>
                    </div>
                      <div class="col-12">
                        <label class="form-label fw-bold">Assigned Driver</label>
                        <input type="text" name="driver" id="modal_driver_display" class="form-control bg-light" readonly required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" readonly required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Odometer (km)</label>
                        <input type="number" name="odometer_reading" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Litres</label>
                        <input type="number" step="0.01" name="litres" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Total Cost (K)</label>
                        <input type="number" step="0.01" name="cost" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Save Fuel Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editFuelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="card-header p-0" style="border:none; height: 10px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 10px;" class="img-fluid w-100">
            </div>
            <form id="editFuelForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Fuel Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Vehicle</label>
                        <input type="text" id="edit_vehicle_display" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" id="edit_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Odometer (km)</label>
                        <input type="number" name="odometer_reading" id="edit_odometer" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Litres</label>
                        <input type="number" step="0.01" name="litres" id="edit_litres" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Total Cost (K)</label>
                        <input type="number" step="0.01" name="cost" id="edit_cost" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Station</label>
                        <input type="text" name="fuel_station" id="edit_station" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Breakdown -->
<div class="modal fade" id="addRepairModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('fleet.storeRepair') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                     <div class="p-0" style="border:none; height: 15px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" class="img-fluid w-100">
            </div>

                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title"><i class="fas fa-tools me-2"></i>Log Vehicle Breakdown</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Vehicle (Plate)</label>
                            <select name="vehicle_id" class="form-select" required>
                                @foreach($vehicles as $v) 
                                    <option value="{{ $v->id }}">{{ $v->registration_number }}</option> 
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Reported By</label>
                            <select name="reported_by" class="form-select" required>
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->name }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Incident Date</label>
                            <input type="date" name="report_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Service Provider / Garage</label>
                            <input type="text" name="service_provider" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="fw-bold">Repair Description</label>
                            <textarea name="repair_description" class="form-control" rows="2" placeholder="e.g. Engine overheating, Flat tire, Gearbox failure" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold text-primary">Repair Cost (ZMW)</label>
                            <input type="number" step="0.01" name="cost" class="form-control border-primary" placeholder="0.00" required>
                            <small class="text-muted italic">Accountant uses this for monthly sums.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold d-block">Vehicle Status</label>
                            <div class="d-flex mt-2">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="downtime_status" id="statusOngoing" value="Ongoing" checked>
                                    <label class="form-check-label text-danger fw-bold" for="statusOngoing">
                                        Ongoing (Off-Road)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="downtime_status" id="statusResolved" value="Resolved">
                                    <label class="form-check-label text-success fw-bold" for="statusResolved">
                                        Resolved
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="fw-bold">Report</label>
                            <input type="text" name="report" class="form-control">
                        </div>

                    
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Breakdown Record</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="p-0" style="border:none; height: 15px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" class="img-fluid w-100">
            </div>

            <form action="{{ route('fleet.store') }}" method="POST">
                @csrf

                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-car me-2"></i>Register New Fleet Vehicle
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Number Plate</label>
                            <input type="text" name="registration_number" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Make</label>
                            <input type="text" name="make" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Model & Year</label>
                            <input type="text" name="model" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Engine Number</label>
                            <input type="text" name="engine_type" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Transmission</label>
                            <select name="transmission" class="form-select">
                                <option value="Manual">Manual</option>
                                <option value="Automatic">Automatic</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mileage</label>
                            <input type="number" name="current_mileage" class="form-control" value="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active">Operational</option>
                                <option value="Repair">Maintenance Required</option>
                                <option value="Disposed">Out of Service</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-primary">Assign To</label>
                            <select name="assigned_driver" class="form-select border-primary" required>
                                <option value="" disabled selected>Select Employee</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->name }}">
                                        {{ $driver->name }} ({{ $driver->department ?? 'Staff' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Vehicle</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Edit Vehicle Modal (One per vehicle) -->
@foreach($vehicles as $vehicle)
<div class="modal fade" id="editVehicleModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
   
    <div class="modal-dialog modal-lg">
          <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
        <form action="{{ route('fleet.update', $vehicle->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header" bg-light text-white>
                    <h5 class="modal-title">Edit Vehicle: {{ $vehicle->registration_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Engine Number</label>
                            <input type="text" name="engine_type" class="form-control" value="{{ $vehicle->engine_type }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Personnel</label>
                            <select name="assigned_driver" class="form-select">
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->name }}" {{ $vehicle->assigned_driver == $driver->name ? 'selected' : '' }}>{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<div class="modal fade" id="editAccidentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="card-header p-0" style="height: 10px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" class="img-fluid w-100" style="height: 10px;">
            </div>
            <form id="editAccidentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title"><i class="ri-edit-line me-2"></i>Edit Incident Report</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vehicle</label>
                        <input type="text" id="edit_acc_vehicle" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Driver</label>
                        <input type="text" id="edit_acc_driver" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="incident_date" id="edit_acc_date" class="form-control" readonly required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" id="edit_acc_location" class="form-control" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Severity</label>
                        <select name="severity" id="edit_acc_severity" class="form-select">
                            <option value="Minor">Minor</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Major">Major</option>
                            <option value="Totaled">Totaled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Insurance Status</label>
                        <select name="insurance_status" id="edit_acc_insurance" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Claimed">Claimed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Est. Cost (K)</label>
                        <input type="number" step="0.01" name="estimated_repair_cost" id="edit_acc_cost" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Police Report #</label>
                        <input type="text" name="police_report_number" id="edit_acc_police" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_acc_desc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Update Incident Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($vehicles as $vehicle)
@php $compliance = $vehicle->compliance; @endphp
<div class="modal fade" id="editComplianceModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            {{-- Branded Pattern Header --}}
            <div class="card-header p-0" style="border:none; height: 15px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
            </div>

            <form action="{{ route('fleet.updateCompliance', $vehicle->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2"></i>Edit Compliance: {{ $vehicle->registration_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Insurance Details --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Insurance Provider</label>
                            <input type="text" name="insurance_provider" class="form-control border-info" 
                                   value="{{ $compliance->insurance_provider ?? '' }}" placeholder="e.g. Madison Insurance">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Policy Number</label>
                            <input type="text" name="insurance_policy_number" class="form-control" 
                                   value="{{ $compliance->insurance_policy_number ?? '' }}" placeholder="Enter policy #">
                        </div>

                        {{-- Expiry Dates --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-primary">Insurance Expiry</label>
                            <input type="date" name="insurance_expiry_date" class="form-control border-primary" 
                                   value="{{ $compliance->insurance_expiry_date ?? '' }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">Road Tax Expiry</label>
                            <input type="date" name="road_tax_expiry" class="form-control border-danger" 
                                   value="{{ $compliance->road_tax_expiry ?? '' }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-success">Fitness Expiry</label>
                            <input type="date" name="fitness_certificate_expiry" class="form-control border-success" 
                                   value="{{ $compliance->fitness_certificate_expiry ?? '' }}" required>
                        </div>

                        {{-- Manual Status Override (Optional) --}}
                        <div class="col-md-12">
                            <div class="p-2 bg-light rounded border">
                                <small class="text-muted italic">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    System will automatically calculate status based on the dates provided above.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white px-4">
                        <i class="fas fa-save me-1"></i> Update Compliance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


<!-- edit service -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content border-0 shadow-lg">
          <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
            <form action="{{ route('fleet.UpdateServiceSchedule') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-tools me-2"></i>Edit Service Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="vehicle_id" id="modal_vehicle_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Service Provider</label>
                            <input type="text" name="service_provider" id="modal_provider" class="form-control border-primary" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Service Type</label>
                            <input type="text" name="service_type" id="modal_type" class="form-control" placeholder="e.g. Major Service">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mileage at Service (km)</label>
                            <input type="number" name="last_service_mileage" id="modal_mileage" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Actual Cost (ZMW)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">K</span>
                                <input type="number" step="0.01" name="estimated_cost" id="modal_cost" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Next Service Date</label>
                            <input type="date" name="next_service_date" id="modal_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vehicle Health Status</label>
                            <select name="service_status" id="modal_status" class="form-select" required>
                                <option value="up-to-date">Healthy / Up to Date</option>
                                <option value="pending">Needs Attention</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>

                  

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Technician Remarks</label>
                            <textarea name="remarks" id="modal_remarks" class="form-control" rows="3" placeholder="Enter service notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addAccidentModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            {{-- Branded Pattern Header --}}
            <div class="card-header p-0" style="border:none; height: 15px;">
                <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
            </div>

            <form action="{{ route('accidents.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title">Log New Incident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-select" required>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->registration_number }}">{{ $v->registration_number }}</option>
                            @endforeach
                        </select>
                    </div>
                  <div class="col-md-6">
                                <label class="form-label fw-bold">Driver at Time of Incident</label>
                                <select name="driver_name" class="form-select select2" required>
                                    <option value="" selected disabled>-- Select Employee --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->name }}">
                                            {{ $driver->name }} {{ $driver->last_name ?? '' }} 
                                            @if($driver->role) ({{ ucfirst($driver->role) }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select the staff member operating the vehicle.</div>
                            </div>
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="incident_date" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Great East Road" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select">
                            <option value="Minor">Minor (Scratches/Dents)</option>
                            <option value="Moderate">Moderate (Driveable but damaged)</option>
                            <option value="Major">Major (Non-driveable)</option>
                            <option value="Totaled">Total Loss</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Police Report #</label>
                        <input type="text" name="police_report_number" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Brief Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Save Incident Report</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- add service modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
          <div class="card-header p-0" style="border:none; height: 15px;">
                    <img src="{{ asset('assets/BG-02.jpeg') }}" style="height: 15px;" alt="Pattern" class="img-fluid w-100">
                </div>
        <form action="{{ route('fleet.storeServiceSchedule') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Create New Service Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="fw-bold">Vehicle (Plate)</label>
                            <select name="vehicle_id" class="form-select " required>
                                @foreach($vehicles as $v) <option value="{{ $v->id }}">{{ $v->registration_number }}</option> @endforeach
                            </select>
                        </div>
   {{-- Service Details --}}
        <div class="col-md-6">
            <label class="fw-bold">Service Type</label>
            <input type="text" name="service_type" class="form-control" placeholder="e.g. Full Service" required>
        </div>
        <div class="col-md-6">
            <label class="fw-bold">Service Provider</label>
            <input type="text" name="service_provider" class="form-control" required>
        </div>

        {{-- Mileage Tracking --}}
        <div class="col-md-6">
            <label class="fw-bold text-primary">Last Service Mileage (km)</label>
            <input type="number" name="last_service_mileage" class="form-control border-primary" required>
        </div>
        <div class="col-md-6">
            <label class="fw-bold text-danger">Next Service Mileage (km)</label>
            <input type="number" name="next_service_mileage" class="form-control border-danger" required>
        </div>

        {{-- Date Tracking --}}
        <div class="col-md-6">
            <label class="fw-bold">Last Service Date</label>
            <input type="date" name="last_service_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="fw-bold">Next Service Date</label>
            <input type="date" name="next_service_date" class="form-control" required>
        </div>

        {{-- Costs & Status --}}
        <div class="col-md-6">
            <label class="fw-bold">Cost (ZMW)</label>
            <div class="input-group">
                <span class="input-group-text">K</span>
                <input type="number" step="0.01" name="estimated_cost" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
    <label class="fw-bold text-muted">Vehicle Status (Current)</label>
    {{-- We show the status from the main vehicle record --}}
    <input type="text" 
           class="form-control bg-light" 
           id="display_vehicle_status" 
           value="Active" 
           readonly>
    {{-- Hidden field to ensure 'status' is still sent if your validator requires it --}}
    <input type="hidden" name="status" id="hidden_vehicle_status" value="Active">
</div>
        <div class="col-md-6">
            <label class="fw-bold">Service Status</label>
            <select name="service_status" class="form-select" required>
                <option value="up-to-date">Up to Date</option>
                <option value="pending">Pending</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>

        <div class="col-md-12">
            <label class="fw-bold">Technician Remarks</label>
            <input type="text" name="remarks" class="form-control" rows="2"></input>
        </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary  px-4">Save Service Record</button>
                </div>
            </div>
        </form>
    </div>
</div>




</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editServiceModal = document.getElementById('editServiceModal');
        if (editServiceModal) {
            editServiceModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                editServiceModal.querySelector('#modal_vehicle_id').value = button.getAttribute('data-vehicle-id');
                editServiceModal.querySelector('#modal_provider').value = button.getAttribute('data-provider') || '';
                editServiceModal.querySelector('#modal_cost').value = button.getAttribute('data-cost') || '';
                editServiceModal.querySelector('#modal_date').value = button.getAttribute('data-date') || '';
            });
        }
    });
    
</script>

<script>
function prepareFuelModal(regNumber, driverName) {
    // Populate the readonly fields
    document.getElementById('modal_vehicle_display').value = regNumber;
    document.getElementById('modal_driver_display').value = driverName;
    
    // Reset other fields for a clean entry
    document.querySelector('#addFuelModal input[name="odometer_reading"]').value = '';
    document.querySelector('#addFuelModal input[name="litres"]').value = '';
    document.querySelector('#addFuelModal input[name="cost"]').value = '';
}

function populateEditModal(log) {
    // Set the form action dynamically
    const form = document.getElementById('editFuelForm');
    form.action = `/fuel/update/${log.id}`;

    // Fill the fields
    document.getElementById('edit_vehicle_display').value = log.vehicle_id;
    document.getElementById('edit_date').value = log.date;
    document.getElementById('edit_odometer').value = log.odometer_reading;
    document.getElementById('edit_litres').value = log.litres;
    document.getElementById('edit_cost').value = log.cost;
    document.getElementById('edit_station').value = log.fuel_station;
}

function setVehicle(regNumber) {
    // This pre-selects the vehicle in the dropdown when you click the row's 'Add' button
    const select = document.getElementById('modal_vehicle_select');
    select.value = regNumber;
}

function populateAccidentEditModal(data) {
    // Set Action URL
    document.getElementById('editAccidentForm').action = `/fleet/accidents/${data.id}`;

    // Map fields
    document.getElementById('edit_acc_vehicle').value = data.vehicle_id;
    document.getElementById('edit_acc_driver').value = data.driver_name;
    document.getElementById('edit_acc_date').value = data.incident_date;
    document.getElementById('edit_acc_location').value = data.location;
    document.getElementById('edit_acc_severity').value = data.severity;
    document.getElementById('edit_acc_insurance').value = data.insurance_status || 'Pending';
    document.getElementById('edit_acc_cost').value = data.estimated_repair_cost;
    document.getElementById('edit_acc_police').value = data.police_report_number;
    document.getElementById('edit_acc_desc').value = data.description;
}
</script>

@endsection