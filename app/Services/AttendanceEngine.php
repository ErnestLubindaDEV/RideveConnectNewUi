<?php
namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\DailyAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceEngine
{
    public function processDailyRecords($date = null)
    {
        $date = $date ?? now()->toDateString();

        // Query the minimum and maximum scan times per employee
        $records = AttendanceLog::whereDate('event_time', $date)
            ->select('employee_id', 
                DB::raw('MIN(event_time) as first_scan'), 
                DB::raw('MAX(event_time) as last_scan')
            )
            ->groupBy('employee_id')
            ->get();

        foreach ($records as $record) {
            $checkIn = Carbon::parse($record->first_scan);
            $checkOut = Carbon::parse($record->last_scan);
            
            // Logic: If they only scanned once, Check-Out remains null or same as Check-In
            $isSameScan = $checkIn->equalTo($checkOut);

            DailyAttendance::updateOrCreate([
                'employee_id' => $record->employee_id,
                'work_date'   => $date,
            ], [
                'check_in'    => $checkIn,
                'check_out'   => $isSameScan ? null : $checkOut,
                'total_hours' => $isSameScan ? 0 : $checkIn->diffInHours($checkOut),
            ]);
        }
    }
}