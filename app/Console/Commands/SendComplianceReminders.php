<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendComplianceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-compliance-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

public function handle()
{
    // 1. Get the target date (30 days from now)
    $cutoffDate = now()->addDays(30)->format('Y-m-d');
    
    $this->info("Scanning for compliance dates expiring on or before: " . $cutoffDate);

    // 2. Query using trim() and uppercase check to ensure 'NO' matches correctly
  $expiringSoon = \App\Models\VehicleCompliance::with('vehicle') // Add this!
    ->where('reminder_sent', 'NO')
    ->where(function($query) use ($cutoffDate) {
        $query->whereDate('insurance_expiry_date', '<=', $cutoffDate)
              ->orWhereDate('road_tax_expiry', '<=', $cutoffDate)
              ->orWhereDate('fitness_certificate_expiry', '<=', $cutoffDate);
    })->get();

    if ($expiringSoon->isEmpty()) {
        $this->warn("Scan complete: No pending reminders found. Possible reasons:");
        $this->line("- All records are already marked as reminder_sent = 'YES'");
        $this->line("- No records have expiry dates <= " . $cutoffDate);
        return;
    }

   foreach ($expiringSoon as $record) {
    try {
        // Define your recipients here
        $recipients = [
            'info@ridevemedia.com',
            'lombe@ridevemedia.com',
            'biam@ridevemedia.com',
            'ernest@ridevemedia.com' 
        ];

        \Mail::to($recipients)->send(new \App\Mail\ComplianceExpiryWarning($record));
        
        $record->reminder_sent = 'YES';
        $record->save();
        
        $this->info("✔ Reminder sent for Vehicle ID: {$record->vehicle_id}");
    } catch (\Exception $e) {
        $this->error("Failed to send mail for {$record->vehicle_id}: " . $e->getMessage());
    }
}
}
}
