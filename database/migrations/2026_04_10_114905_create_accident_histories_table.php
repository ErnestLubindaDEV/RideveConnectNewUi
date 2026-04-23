<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('accident_histories', function (Blueprint $table) {
        $table->id();
        $table->string('vehicle_id'); // Registration Number
        $table->string('driver_name');
        $table->date('incident_date');
        $table->string('location');
        $table->text('description')->nullable();
        $table->string('severity'); // Minor, Moderate, Major, Totaled
        $table->string('police_report_number')->nullable();
        $table->string('insurance_status')->default('Pending');
        $table->decimal('estimated_repair_cost', 15, 2)->nullable();
        $table->timestamps();
        
        // Optional: Link to vehicles table if registration_number is a unique key
        // $table->foreign('vehicle_id')->references('registration_number')->on('vehicles');
    });
}

public function down(): void
{
    Schema::dropIfExists('accident_histories');
}
};
