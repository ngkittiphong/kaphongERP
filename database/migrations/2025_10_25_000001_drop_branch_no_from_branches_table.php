<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'branch_no')) {
                // Drop unique and index constraints if they exist
                try { $table->dropUnique('branches_branch_no_unique'); } catch (\Throwable $e) { /* ignore */ }
                try { $table->dropIndex('branches_branch_no_index'); } catch (\Throwable $e) { /* ignore */ }

                $table->dropColumn('branch_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (!Schema::hasColumn('branches', 'branch_no')) {
                $table->string('branch_no', 20)->nullable();
                // Recreate indexes as they were
                $table->unique('branch_no');
                $table->index('branch_no');
            }
        });
    }
};

