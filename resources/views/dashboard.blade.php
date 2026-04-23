@extends('partials.layouts.master')

@section('title', 'Rideve Connect | Dashboard')
@section('title-sub', 'Dashboard')
@section('pagetitle', 'Rideve Media')
@section('css')
    <!-- Picker CSS -->
    <link rel="stylesheet" href="assets/libs/air-datepicker/air-datepicker.css">
@endsection

@section('content')

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="row">
            <div class="col-xxl-12">
                <div class="row">
       <div class="col-md-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-4 pb-3">
                <div>
                    <h3>{{ $totalEmployees }}</h3>
                    <p class="text-muted mb-0">Employees</p>
                </div>
                <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-primary-subtle text-primary fs-3">
                    <i class="ri-team-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-4 pb-3">
                <div>
                    <h3>{{ $departmentsCount}}</h3>
                    <p class="text-muted mb-0">Departments</p>
                </div>
                <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-info-subtle text-info fs-3">
                    <i class="ri-building-4-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-4 pb-3">
                <div>
                    <h3>{{ $totalAssets }}</h3>
                    <p class="text-muted mb-0"> Assets</p>
                </div>
                <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-success-subtle text-success fs-3">
                    <i class="ri-computer-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-4 pb-3">
                <div>
                    <h3>{{ $totalProducts}}</h3>
                    <p class="text-muted mb-0">Total Inventory</p>
                </div>
                <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-warning-subtle text-warning fs-3">
                    <i class="ri-box-3-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Avg. Clock-In Time</p>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $formattedAvgTime }} AM</h4>
                    <span class="badge bg-soft-info text-info"><i class="ri-time-line align-bottom"></i> Daily Average</span>
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-primary rounded fs-3">
                        <i class="ri-user-follow-line text-primary"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

       <div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <h5 class="card-title">RideveConnect System Activity</h5>

                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-primary-subtle text-primary">HRM</span>
                            <span class="text-muted fs-13">Attendance</span>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-success-subtle text-success">Inventory</span>
                            <span class="text-muted fs-13">Stock</span>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-warning-subtle text-warning">Production</span>
                            <span class="text-muted fs-13">Projects</span>
                        </div>
                    </div>
                </div>

                <div id="rideveconnect-activity-chart"></div>

            </div>
        </div>
    </div>
    

   <div class="col-xl-4">
    <div class="card card-h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Agency Insights</h5>
            <span class="badge bg-success-subtle text-success">Live</span>
        </div>

        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="h-44px w-44px d-flex justify-content-center align-items-center rounded bg-info-subtle text-info fs-5 flex-shrink-0">
                    <i class="ri-chat-quote-line"></i>
                </div>
                <div>
                    <h6 class="fw-medium mb-1">Daily Inspiration</h6>
                    <p class="text-muted mb-1" style="font-style: italic;">"{{ $dailyQuote['q'] }}"</p>
                    <small class="text-primary fw-bold">— {{ $dailyQuote['a'] }}</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="h-44px w-44px d-flex justify-content-center align-items-center rounded bg-primary-subtle text-primary fs-5 flex-shrink-0">
                    <i class="ri-team-line"></i>
                </div>
                <div>
                    <h6 class="fw-medium mb-1">HRM Activity</h6>
                    <p class="text-muted mb-0">Employee attendance and leave activity.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="h-44px w-44px d-flex justify-content-center align-items-center rounded bg-success-subtle text-success fs-5 flex-shrink-0">
                    <i class="ri-box-3-line"></i>
                </div>
                <div>
                    <h6 class="fw-medium mb-1">Inventory Tracking</h6>
                    <p class="text-muted mb-0">Stock movement and warehouse updates.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
                    <div class="col-xl-6">
    <div class="card card-h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Employees on Leave</h6>

            {{-- Optional: quick count badge --}}
            <span class="badge bg-warning-subtle text-warning">
                {{ $ongoingLeave->count() }} Ongoing
            </span>
        </div>

        <div class="card-body">
            @forelse($ongoingLeave as $leave)
                @php
                    $status = strtolower($leave->status ?? 'pending');

                    $statusClass = match($status) {
                        'approved' => 'bg-success-subtle text-success',
                        'ongoing'  => 'bg-info-subtle text-info',
                        'completed'=> 'bg-secondary-subtle text-secondary',
                        'rejected' => 'bg-danger-subtle text-danger',
                        default    => 'bg-warning-subtle text-warning',
                    };

                    $avatarPath = $leave->profile_picture ?? null; // adjust field name if different
                    $leaveFrom  = $leave->leave_from ?? null;
                    $leaveTo    = $leave->leave_to ?? null;

                    $fromText = $leaveFrom ? \Carbon\Carbon::parse($leaveFrom)->format('d M Y') : 'N/A';
                    $toText   = $leaveTo ? \Carbon\Carbon::parse($leaveTo)->format('d M Y') : 'N/A';
                @endphp

                <div class="mb-4 pb-3 border-bottom">
                    {{-- Right side: avatar group --}}
                    <div class="avatar-group float-end">
                        <a href="javascript:void(0)" class="avatar-item" title="{{ $leave->full_name ?? 'Employee' }}">
                            @if(!empty($avatarPath))
                                <img class="img-fluid avatar-sm rounded-circle"
                                     src="{{ Str::startsWith($avatarPath, ['http://','https://']) ? $avatarPath : asset('storage/'.$avatarPath) }}"
                                     alt="{{ $leave->full_name ?? 'Employee' }}">
                            @else
                                <span class="avatar-title bg-light text-muted rounded-circle">
                                    <i class="ri-user-3-line"></i>
                                </span>
                            @endif
                        </a>
                    </div>

                    {{-- Left side: leave row --}}
                    <div class="d-flex gap-3 align-items-center">
                        <div class="h-48px w-48px d-flex justify-content-center align-items-center bg-warning-subtle rounded-2 text-warning">
                            <i class="ri-suitcase-2-line fs-5"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                {{ $leave->full_name ?? 'Unknown Employee' }}
                            </h6>

                            <p class="mb-0 fs-13 text-muted">
                                {{ $leave->leave_type ?? 'Leave' }}
                                <span class="mx-1">•</span>
                                {{ $fromText }} → {{ $toText }}
                            </p>
                        </div>

                        <div class="text-end">
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($leave->status ?? 'Pending') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <div class="mb-2">
                        <i class="ri-time-line fs-1"></i>
                    </div>
                    <div>No employees currently on leave.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
                   <div class="col-xl-6">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Stock Movement</h6>
        </div>

        <div class="card-body">
            <section data-simplebar class="px-4 mx-n4" style="max-height: 340px;">
                <div class="timeline2">
                    <ul>
                        @forelse($recentStockActivities as $activity)
                            @php
                                $isAddition = ($activity->type ?? '') === 'Addition';
                                $qty = $isAddition ? ($activity->quantity_added ?? 0) : ($activity->stock_deducted ?? 0);

                                $icon = $isAddition ? 'ri-add-circle-fill' : 'ri-indeterminate-circle-fill';
                                $pillBg = $isAddition ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';

                                $borderClass = $isAddition ? 'border-success' : 'border-danger';
                                $sign = $isAddition ? '+' : '-';

                                $productName = optional($activity->product)->name ?? 'Unknown Product';
                                $timeLabel = optional($activity->created_at)->format('H:i') ?? '';
                                $humanTime = optional($activity->created_at)->diffForHumans() ?? '';
                            @endphp

                            <li class="card border-0 box border-start ps-3 mb-3 {{ $borderClass }}" style="border-width: 4px;">
                                <span></span>

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="h-40px w-40px d-flex justify-content-center align-items-center rounded-pill {{ $pillBg }}">
                                            <i class="{{ $icon }} fs-5"></i>
                                        </div>

                                        <div>
                                            <h6 class="mb-1">{{ $productName }}</h6>
                                            <p class="fs-12 text-muted mb-0">
                                                {{ $isAddition ? 'Stock Added' : 'Stock Deducted' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="text-muted mb-1">{{ $timeLabel }}</div>
                                        <span class="badge {{ $isAddition ? 'bg-success' : 'bg-danger' }}">
                                            {{ $sign }}{{ number_format($qty) }} units
                                        </span>
                                    </div>
                                </div>

                                <p class="text-muted mb-0 fs-13">
                                    {{ $humanTime }}
                                </p>
                            </li>
                        @empty
                            <li class="card border-0 box">
                                <div class="text-center text-muted py-4">
                                    <i class="ri-inbox-2-line fs-2 d-block mb-2"></i>
                                    No recent stock movements
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </section>
        </div>
    </div>
</div>
                </div>
            </div>
            
                <!-- <div class="row">
                    <div class="col-md-6 col-xl-3 col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-4">
                                    <div
                                        class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-light-subtle text-muted fs-4">
                                        <i class="bi bi-wallet"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 count" data-count="8521">0</h4>
                                        <p class="text-muted mb-0">Total Project</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="fw-medium fs-13 text-success">+ 2.9%</div>
                                    <div id="spark1" class="apexcharts-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-4">
                                    <div
                                        class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-light-subtle text-muted fs-4">
                                        <i class="bi bi-calendar2-check"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 count" data-count="7125">0</h4>
                                        <p class="text-muted mb-0">Completed Projects</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="fw-medium fs-13 text-success">+ 2.7%</div>
                                    <div id="spark2" class="apexcharts-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-4">
                                    <div
                                        class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-light-subtle text-muted fs-4">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 count" data-count="4525">0</h4>
                                        <p class="text-muted mb-0">Pending Projects</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="fw-medium fs-13 text-success">+ 1.9%</div>
                                    <div id="spark3" class="apexcharts-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-4">
                                    <div
                                        class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-light-subtle text-muted fs-4">
                                        <i class="bi bi-hourglass"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 count" data-count="3562">0</h4>
                                        <p class="text-muted mb-0">Upcoming Deadlines</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="fw-medium fs-13 text-success">+ 2.1%</div>
                                    <div id="spark4" class="apexcharts-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Projects Calendar</h6>
                        <button class="btn btn-outline-light btn-sm text-muted">Report<i
                                class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light-subtle">
                                    <div class="card-body">
                                        <span class="bullet me-2 bg-success"></span><span class="text-muted fs-12">7, Feb
                                            2025</span>
                                        <h6 class="mt-4 mb-0">Meeting with Client</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light-subtle">
                                    <div class="card-body">
                                        <span class="bullet me-2 bg-danger"></span><span class="text-muted fs-12">23, Feb
                                            2025</span>
                                        <h6 class="mt-4 mb-0">Deal with New Client</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="datepicker-container">
                                    <input type="text" class="form-control d-none" id="inline-picker"
                                        placeholder="Select a date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- <div class="col-xl-5 col-xxl-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Team Member</h6>
                        <button class="btn btn-outline-light text-muted btn-sm">See All<i
                                class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                    <div class="card-body">
                        <section data-simplebar class="px-5 mx-n5" style="max-height: 436px;">
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-1.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">John Doe</h6>
                                            <p class="fs-12 text-muted mb-0 max-w-112px min-w-112px text-truncate">
                                                UI / UX Designer</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">23</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">1h 23m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">40%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-2.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">Kael Drift</h6>
                                            <p class="fs-12 text-muted mb-0 max-w-112px min-w-112px text-truncate">
                                                Develop Mobile Application</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">65</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">3h 45m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm circular-progress-success"
                                            style="--progress: 80;">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">80%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-3.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">Elara Vex</h6>
                                            <p class="fs-12 text-muted mb-0 max-w-112px min-w-112px text-truncate">
                                                Web Designer</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">45</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">2h 52m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm circular-progress-warning"
                                            style="--progress: 71;">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">71%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-4.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">Soren Thorne</h6>
                                            <p class="fs-12 text-muted mb-0 max-w-112px min-w-112px text-truncate">
                                                Development</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">23</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">1h 23m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm circular-progress-info"
                                            style="--progress: 60;">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">60%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-5.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">Lira Solace</h6>
                                            <p class="fs-12 text-muted mb-0 max-w-112px min-w-112px text-truncate">
                                                UI / UX Designer</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">12</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">4h 23m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm circular-progress-secondary"
                                            style="--progress: 30;">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">30%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border rounded-2 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="assets/images/avatar/avatar-6.jpg" alt="Avatar Image"
                                            class="avatar-lg rounded-pill">
                                        <div>
                                            <h6 class="mb-1">Varek Stryde</h6>
                                            <p class="fs-12 text-muted mb-0">UI / UX Designer</p>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">
                                        <p class="mb-1">Tasks : <span class="text-body">23</span></p>
                                        <p class="mb-0">Hours : <span class="text-body">1h 23m</span></p>
                                    </div>
                                    <div>
                                        <div class="circular-progress circular-sm circular-progress-success"
                                            style="--progress: 80;">
                                            <svg class="circular-inner" viewBox="0 0 56 56">
                                                <circle class="bg-circular-progress"></circle>
                                                <circle class="fg-circular-progress"></circle>
                                            </svg>
                                            <div class="circular-text">40%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div> -->
     
           <div class="col-xl-7 col-xxl-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Ongoing Projects</h6>
            <a href="{{ route('projects.manage') }}" class="btn btn-outline-light text-muted btn-sm">
                See All <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-box table-responsive">
                <table class="table text-nowrap align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Project Type</th>
                            <th scope="col">Assigned To</th>
                            <th scope="col">Estimated Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ongoingProjects as $project)
                            @php
                                $status = $project->status ?? 'Unknown';

                                $statusClass = match($status) {
                                    'Printing' => 'bg-primary-subtle text-primary',
                                    'Welding' => 'bg-secondary-subtle text-secondary',
                                    'Trimming' => 'bg-warning-subtle text-warning',
                                    'Pasting' => 'bg-info-subtle text-info',
                                    'Heat press' => 'bg-danger-subtle text-danger',
                                    'Peeling Off' => 'bg-success-subtle text-success',
                                    'Final Heat press' => 'bg-dark-subtle text-dark',
                                    'Delivery / collection' => 'bg-secondary-subtle text-secondary',
                                    'Client Approval Pending' => 'bg-warning-subtle text-warning',
                                    default => 'bg-light text-muted',
                                };

                                $assignedEmployees = json_decode($project->assigned_employees, true) ?? [];
                            @endphp

                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">{{ $project->client_name ?? 'N/A' }}</h6>
                                        <p class="mb-0 fs-12 text-muted">
                                            ID: {{ $project->id }}
                                        </p>
                                    </div>
                                </td>

                                <td>{{ $project->project_type ?? 'N/A' }}</td>

                                <td>
                                    @if(count($assignedEmployees))
                                        <span class="text-muted">{{ implode(', ', $assignedEmployees) }}</span>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $project->estimated_time ?? 0 }} mins
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td>
                                    {{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d M Y') : 'N/A' }}
                                </td>

                                <td>
                                    <a href="{{ route('viewProject', $project->id) }}" class="btn btn-light-primary icon-btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No ongoing projects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap gap-4 align-items-center p-4">
                <div class="fw-medium">
                    Showing {{ $ongoingProjects->count() }} ongoing project(s)
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
        <!-- Submit Section -->

    </div>
    </main>
@endsection

@section('js')
    <!-- Datepicker Js -->
    <script src="assets/libs/air-datepicker/air-datepicker.js"></script>
    <script src="assets/libs/chart.js/chart.umd.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <!-- File js -->
    <script src="assets/js/dashboard/project.init.js"></script>
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>

    <script>

var options = {
    chart: {
        type: 'area',
        height: 320,
        toolbar: { show: false }
    },

    series: [
        {
            name: "HRM Attendance",
            data: [22, 25, 24, 26, 28, 27, 29]
        },
        {
            name: "Inventory Movements",
            data: [10, 15, 12, 18, 14, 16, 20]
        },
        {
            name: "Production Tasks",
            data: [5, 7, 6, 8, 9, 10, 12]
        }
    ],

    xaxis: {
        categories: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]
    },

    colors: ['#4f46e5','#16a34a','#f59e0b'],

    stroke: {
        curve: 'smooth',
        width: 3
    },

    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.45,
            opacityTo: 0.1
        }
    },

    legend: {
        position: 'top'
    }

};

var chart = new ApexCharts(document.querySelector("#rideveconnect-activity-chart"), options);
chart.render();

</script>
@endsection
