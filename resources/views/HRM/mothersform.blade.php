@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Pages')
@section('pagetitle', 'Blank')
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

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    /* 1. Remove the default 'Today' background */
    .fc .fc-daygrid-day.fc-day-today {
        background-color: transparent !important;
    }

    /* 2. Optional: Add a simple ring around today's number so you still know it's today */
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        border: 2px solid #727cf5;
        border-radius: 50%;
        padding: 2px 6px;
    }

    /* 3. The highlight class for booked days */
    .booked-day {
        background-color: rgba(114, 124, 245, 0.1) !important;
        border: 2px solid #727cf5 !important;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
         <div class="card-header p-0"  style=" border:none;  height: 20px; ">
                <img  src="../assets/BG-02.jpeg" style=" border: none; max-width: auto; height: 15px;   padding-left:0px" alt="Pattern" class="img-fluid w-100">
            </div>
        <div class="card-header">
            <h3 class="card-title">Mother's Day Planning - {{ $currentMonth }}</h3>
            <p class="text-muted">Fill in the details below to submit your request. 
            <strong>Note:</strong> Mondays and Fridays are unavailable and have been greyed out.</p>
        </div>
        <div class="card-body">
            
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
            <form action="{{ route('mothers-day.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>Employee Name</label>
                    <input type="text" name="employee_name" class="form-control" placeholder="Enter your name" readonly value="{{ auth()->user()->name }}">
                </div>
                @error('selected_date')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
                <div class="form-group mb-3">
                  <label>Select Date</label>
                  <input type="date" id="mothers_day_calendar" name="selected_date" class="form-control" placeholder="Choose a date" required>
              </div>

                <div class="form-group mb-3">
                    <label>Reason</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Why are you requesting this day?" required></textarea>
                </div>

                  <form action="{{ route('mothers-day.store') }}" method="POST">
                     @csrf
                  <button class="btn btn-primary" type="submit">Submit</button>
                 </form>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
<script>
    flatpickr("#mothers_day_calendar", {
        disable: [
            function(date) {
                // 1 is Monday, 5 is Friday
                return (date.getDay() === 1 || date.getDay() === 5);
            }
        ],
        locale: {
            firstDayOfWeek: 1 // Start week on Monday
        }
    });
</script>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventData = @json($events); // Data from your HRMController

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: eventData,
            
            // This adds the custom highlight to ONLY booked days
            dayCellDidMount: function(info) {
                let cellDate = info.date.toLocaleDateString('en-CA'); 
                let isBooked = eventData.some(e => e.start === cellDate);

                if (isBooked) {
                    info.el.style.backgroundColor = 'rgba(40, 167, 69, 0.1)'; // Light Green tint
                    info.el.style.border = '2px solid #28a745'; // Green border
                }
            },

            dateClick: function(info) {
                document.getElementById('mothers_day_calendar').value = info.dateStr;
            }
        });
        calendar.render();
    });
</script>
@endsection