<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Str;



class SyncAttendance extends Command
{
    protected $signature = 'attendance:sync';
    protected $description = 'Sync attendance logs from Hikvision device';

public function handle()
{
    $deviceIp = config('services.hikvision.ip');
    $user = config('services.hikvision.user');
    $pass = config('services.hikvision.pass');

    $this->info("Refining Parameters (Clean Values) at: http://{$deviceIp}");

    // Use a simple integer for searchID and clean date format (No T or Z)
    $startTime = now()->startOfDay()->format('Y-m-d\TH:i:s');
    $endTime = now()->endOfDay()->format('Y-m-d\TH:i:s');

    $payload = [
        "AcsEventCond" => [
            "searchID" => "1", // Simplified ID
            "searchResultPosition" => 0,
            "maxResults" => 100,
            "major" => 0,
            "minor" => 0,
            "startTime" => $startTime,
            "endTime" => $endTime
        ]
    ];

    try {
        $url = "http://{$deviceIp}/ISAPI/AccessControl/AcsEvent?format=json";

        $response = Http::withDigestAuth($user, $pass)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        $this->info("Status: " . $response->status());

        if ($response->successful()) {
            $data = $response->json();
            
            // The response key for this specific structure is usually 'AcsEvent'
            $infoList = $data['AcsEvent']['InfoList'] ?? 
                        $data['AcsEventSearchDescription']['InfoList'] ?? 
                        null;

            if (!$infoList) {
                $this->warn("200 OK, but InfoList is missing or empty.");
                $this->line(json_encode($data));
                return;
            }

            $count = count($infoList);
            foreach ($infoList as $event) {
                \App\Models\AttendanceLog::updateOrCreate([
                    'employee_id' => (string) ($event['employeeNoString'] ?? $event['cardNo'] ?? '0'),
                    'event_time'  => \Carbon\Carbon::parse($event['time']),
                ], ['device_ip' => $deviceIp]);
            }

            $this->info("Success! Saved $count logs.");
            (new \App\Services\AttendanceEngine())->processDailyRecords(now()->toDateString());

        } else {
            $this->error("Still rejecting values.");
            $this->line($response->body());
        }
    } catch (\Exception $e) {
        $this->error("Connection Error: " . $e->getMessage());
    }
}
}