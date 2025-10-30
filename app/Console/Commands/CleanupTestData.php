<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Options:
     *  --dry   Do not delete anything, just show what would be deleted
     */
    protected $signature = 'app:cleanup-test-data {--dry : Preview deletions without applying} {--force : Bypass confirmation prompt}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up test/demo data across key tables while preserving users, roles, and critical references.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry');
        $force  = (bool) $this->option('force');

        // IMPORTANT: Only include tables that are safe to purge for test data
        // Keep auth/permission tables and core lookups intact
        $tablesToWipe = [
            // company structure
            'companies',
            'branches',
            'warehouses',
            // domain data
            'products',
            'warehouses_products',
            'inventories',
            'transfer_slips',
            'transfer_slip_details',
            'warehouse_transfers',
            'check_stock_reports',
            'check_stock_details',
            // optional logs/history tables (add if exist)
            'activity_log',
        ];

        $existingTables = collect($tablesToWipe)
            ->filter(fn ($t) => \Schema::hasTable($t))
            ->values()
            ->all();

        if (empty($existingTables)) {
            $this->warn('No known data tables found to clean. Nothing to do.');
            return self::SUCCESS;
        }

        $this->info('The following tables will be cleaned:');
        foreach ($existingTables as $table) {
            $this->line(" - {$table}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->info('Dry run mode: No data will be deleted.');
            foreach ($existingTables as $table) {
                $count = DB::table($table)->count();
                $this->line(str_pad($table, 30) . "rows: {$count}");
            }
            return self::SUCCESS;
        }

        if (!$force && !$this->confirm('Are you sure you want to DELETE ALL DATA in these tables? This cannot be undone.')) {
            $this->warn('Aborted.');
            return self::SUCCESS;
        }

        DB::beginTransaction();
        try {
            // Disable FK checks for MySQL and compatible drivers
            $driver = DB::getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            } elseif ($driver === 'pgsql') {
                // handled per table with CASCADE where possible
            }

            foreach ($existingTables as $table) {
                // Prefer truncate for speed, fallback to delete if not supported
                try {
                    DB::table($table)->truncate();
                    $this->line("Truncated {$table}");
                } catch (\Throwable $e) {
                    DB::table($table)->delete();
                    $this->line("Deleted rows from {$table}");
                }
            }

            // Re-enable FKs
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }

            DB::commit();
            $this->info('Test data cleanup completed successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Cleanup failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}


