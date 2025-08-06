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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('first_name')->after('guests');
            $table->string('last_name')->after('first_name');
            $table->string('email')->after('last_name');
            $table->string('phone', 20)->after('email');
            $table->string('company')->nullable()->after('phone');
            $table->string('booking_reference')->unique()->after('special_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name', 
                'email',
                'phone',
                'company',
                'booking_reference'
            ]);
        });
    }
};
