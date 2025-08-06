<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index('email');
            $table->index('created_at');
            $table->index(['created_at', 'email']);
            
            // Add status column for tracking message processing
            $table->enum('status', ['pending', 'processed', 'replied'])->default('pending')->after('ip_address');
            $table->index('status');
            
            // Add processed_at timestamp
            $table->timestamp('processed_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['created_at', 'email']);
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'processed_at']);
        });
    }
};
