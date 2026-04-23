<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mothers_day_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Connects to the employee
        $table->string('employee_name');
        $table->date('selected_date');
        $table->text('reason');
        $table->timestamps(); // Records when they submitted
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mothers_day');
    }
};
