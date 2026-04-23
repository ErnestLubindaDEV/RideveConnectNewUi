<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
{
    // 1. Add the column to users if it's not there
    if (!Schema::hasColumn('users', 'phone_number')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->unique()->after('email');
        });
    }

    // 2. Sync the data using users.id = employees.user_id
    // I am using 'employees' as the table name - change it if your table is named 'h_r_m_s' or 'hrm'
    DB::table('users')
        ->join('employees', 'users.id', '=', 'employees.user_id')
        ->update(['users.phone_number' => DB::raw('employees.phone_number')]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }
};