<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if indexes already exist before adding them
        $indexes = DB::select("SHOW INDEX FROM jobs WHERE Key_name IN ('jobs_queue_index', 'jobs_reserved_at_index', 'jobs_available_at_index')");
        $existingIndexes = collect($indexes)->pluck('Key_name')->toArray();
        
        Schema::table('jobs', function (Blueprint $table) use ($existingIndexes) {
            if (!in_array('jobs_queue_index', $existingIndexes)) {
                $table->index(['queue']);
            }
            if (!in_array('jobs_reserved_at_index', $existingIndexes)) {
                $table->index(['reserved_at']);
            }
            if (!in_array('jobs_available_at_index', $existingIndexes)) {
                $table->index(['available_at']);
            }
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Only drop indexes if they exist
            $indexes = DB::select("SHOW INDEX FROM jobs WHERE Key_name IN ('jobs_queue_index', 'jobs_reserved_at_index', 'jobs_available_at_index')");
            $existingIndexes = collect($indexes)->pluck('Key_name')->toArray();
            
            if (in_array('jobs_queue_index', $existingIndexes)) {
                $table->dropIndex(['queue']);
            }
            if (in_array('jobs_reserved_at_index', $existingIndexes)) {
                $table->dropIndex(['reserved_at']);
            }
            if (in_array('jobs_available_at_index', $existingIndexes)) {
                $table->dropIndex(['available_at']);
            }
        });
    }
};
