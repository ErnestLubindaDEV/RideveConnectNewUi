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
        Schema::create('fuel_logs', function (Blueprint $table) {
    $table->id();
    $table->string('vehicle_id'); // Matches registration_number
    $table->date('date');
    $table->string('fuel_type');
    $table->decimal('litres', 8, 2);
    $table->decimal('cost', 10, 2);
    $table->integer('odometer_reading');
    $table->string('fuel_station')->nullable();
    $table->string('driver');
    $table->decimal('km_per_litre', 8, 2)->nullable();
    $table->text('remarks')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
