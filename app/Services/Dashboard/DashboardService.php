<?php

namespace App\Services\Dashboard;

use App\Models\Branch;
use App\Models\CheckStockReport;
use App\Models\Inventory;
use App\Models\TransferSlip;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Services\InventoryService;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private CacheRepository $cache;

    public function __construct(?CacheRepository $cache = null)
    {
        $this->cache = $cache ?? Cache::store();
    }

    /**
     * Get KPI summary metrics for the dashboard header.
     */
    public function getKpiSummary(User $user): array
    {
        $cacheTtl = (int) config('dashboard.cache_ttl', 300);
        $cacheKey = $this->cacheKey('kpis', $user->id);

        return $this->cache->remember($cacheKey, $cacheTtl, function () {
            $today = Carbon::today();
            $monthStart = Carbon::now()->startOfMonth();

            $salesToday = $this->calculateSalesTotal($today, Carbon::now());
            $salesMonth = $this->calculateSalesTotal($monthStart, Carbon::now());

            $pendingTransferSlips = TransferSlip::query()
                ->whereHas('status', function ($query) {
                    $query->whereIn('name', ['Pending', 'In Transit']);
                })
                ->count();

            $lowStockSkus = WarehouseProduct::query()
                ->join('products', 'products.id', '=', 'warehouses_products.product_id')
                ->whereRaw(
                    'warehouses_products.balance <= COALESCE(NULLIF(products.minimum_quantity, 0), ?)',
                    [config('dashboard.low_stock_fallback_threshold', 5)]
                )
                ->distinct('warehouses_products.product_id')
                ->count('warehouses_products.product_id');

            $activeBranches = Branch::query()->active()->count();

            return [
                'totals' => [
                    'sales_today' => (float) $salesToday,
                    'sales_mtd' => (float) $salesMonth,
                    'pending_transfer_slips' => (int) $pendingTransferSlips,
                    'low_stock_skus' => (int) $lowStockSkus,
                    'active_branches' => (int) $activeBranches,
                ],
                'currency' => currency_code(),
                'last_updated' => Carbon::now(),
            ];
        });
    }

    /**
     * Inventory health datasets: top movers, low stock, capacity utilisation, aging inventory.
     */
    public function getInventoryHealth(User $user): array
    {
        $cacheTtl = (int) config('dashboard.cache_ttl', 300);
        $cacheKey = $this->cacheKey('inventory-health', $user->id);

        return $this->cache->remember($cacheKey, $cacheTtl, function () {
            return [
                'top_movers' => $this->getTopMovers(),
                'low_stock' => $this->getLowStock(),
                'branch_capacity' => $this->getBranchCapacityUtilisation(),
                'aging' => $this->getAgingInventory(),
            ];
        });
    }

    /**
     * Time-series dataset for the performance switcher.
     */
    public function getPerformanceSeries(User $user, string $timeframe = 'monthly'): array
    {
        $cacheTtl = (int) config('dashboard.cache_ttl', 300);
        $cacheKey = $this->cacheKey("performance-{$timeframe}", $user->id);

        return $this->cache->remember($cacheKey, $cacheTtl, function () use ($timeframe) {
            $buckets = match ($timeframe) {
                'weekly' => $this->buildWeeklyBuckets(8),
                'daily' => $this->buildDailyBuckets(14),
                default => $this->buildMonthlyBuckets(6),
            };

            $salesSeries = $this->aggregateSeries(
                $buckets,
                Inventory::query()
                    ->select(DB::raw('SUM(ABS(quantity_move) * COALESCE(NULLIF(avr_sale_price, 0), avr_buy_price)) as value'))
                    ->where('move_type_id', InventoryService::MOVE_TYPE_STOCK_OUT),
                'date_activity'
            );

            $transferSeries = $this->aggregateSeries(
                $buckets,
                TransferSlip::query()
                    ->select(DB::raw('COUNT(*) as value'))
                    ->whereNotNull('date_request'),
                'date_request'
            );

            $adjustmentSeries = $this->aggregateSeries(
                $buckets,
                Inventory::query()
                    ->select(DB::raw('SUM(ABS(quantity_move)) as value'))
                    ->where('move_type_id', InventoryService::MOVE_TYPE_ADJUSTMENT),
                'date_activity'
            );

            return [
                'timeframe' => $timeframe,
                'series' => [
                    [
                        'key' => 'sales',
                        'label' => __t('dashboard.performance.sales', 'Sales'),
                        'color' => '#3b82f6',
                        'points' => $salesSeries,
                    ],
                    [
                        'key' => 'transfers',
                        'label' => __t('dashboard.performance.transfers', 'Transfer Requests'),
                        'color' => '#10b981',
                        'points' => $transferSeries,
                    ],
                    [
                        'key' => 'adjustments',
                        'label' => __t('dashboard.performance.adjustments', 'Adjustments'),
                        'color' => '#f97316',
                        'points' => $adjustmentSeries,
                    ],
                ],
            ];
        });
    }

    /**
     * Alerts that need attention.
     */
    public function getActionAlerts(User $user): array
    {
        $cacheTtl = (int) config('dashboard.cache_ttl', 300);
        $cacheKey = $this->cacheKey('alerts', $user->id);

        return $this->cache->remember($cacheKey, $cacheTtl, function () {
            $alerts = [];

            $lowStockItems = $this->getLowStock(3);
            foreach ($lowStockItems as $item) {
                $alerts[] = [
                    'type' => 'low_stock',
                    'label' => __t('dashboard.alerts.low_stock', 'Low stock alert'),
                    'details' => sprintf('%s (%s) @ %s', $item['name'], $item['sku'], $item['warehouse_name']),
                    'severity' => 'warning',
                    'link' => url('/menu/menu_warehouse_stock'),
                ];
            }

            $overdueTransfers = TransferSlip::query()
                ->with(['status'])
                ->whereHas('status', function ($query) {
                    $query->whereIn('name', ['Pending', 'In Transit']);
                })
                ->whereDate('date_request', '<=', Carbon::now()->subDays(2))
                ->orderBy('date_request', 'asc')
                ->limit(3)
                ->get();

            foreach ($overdueTransfers as $slip) {
                $alerts[] = [
                    'type' => 'stalled_transfer',
                    'label' => __t('dashboard.alerts.transfer_overdue', 'Transfer needs attention'),
                    'details' => sprintf('#%s · %s', $slip->transfer_slip_number, optional($slip->status)->name ?? 'Pending'),
                    'severity' => 'danger',
                    'link' => url('/menu/menu_warehouse_transfer'),
                ];
            }

            $openStockChecks = CheckStockReport::query()
                ->with('warehouse')
                ->where('closed', false)
                ->orderBy('datetime_create', 'desc')
                ->limit(3)
                ->get();

            foreach ($openStockChecks as $report) {
                $alerts[] = [
                    'type' => 'open_stock_check',
                    'label' => __t('dashboard.alerts.open_stock_check', 'Open stock check'),
                    'details' => sprintf(
                        '%s · %s',
                        optional($report->warehouse)->name ?? __t('dashboard.labels.unknown_warehouse', 'Unknown warehouse'),
                        $report->datetime_create?->diffForHumans() ?? ''
                    ),
                    'severity' => 'info',
                    'link' => url('/menu/menu_warehouse_checkstock'),
                ];
            }

            return [
                'alerts' => $alerts,
            ];
        });
    }

    /**
     * Latest activity collections (transfers, stock checks, user updates).
     */
    public function getActivityStream(User $user): array
    {
        $cacheTtl = (int) config('dashboard.cache_ttl', 180);
        $cacheKey = $this->cacheKey('activity', $user->id);
        $limit = (int) config('dashboard.activity_limit', 6);

        return $this->cache->remember($cacheKey, $cacheTtl, function () use ($limit) {
            return [
                'transfers' => TransferSlip::query()
                    ->with(['status', 'warehouseOrigin', 'warehouseDestination'])
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get(),
                'stock_checks' => CheckStockReport::query()
                    ->with('warehouse')
                    ->orderBy('datetime_create', 'desc')
                    ->limit($limit)
                    ->get(),
                'user_events' => User::query()
                    ->with(['profile', 'status'])
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get(),
            ];
        });
    }

    /**
     * Quick actions filtered by user permissions.
     */
    public function getQuickActions(User $user): array
    {
        $actions = collect(config('dashboard.quick_actions', []));

        return $actions
            ->filter(function (array $action) use ($user) {
                $permission = Arr::get($action, 'permission');

                return empty($permission) || $user->hasMenuAccess($permission);
            })
            ->map(function (array $action) {
                $path = Arr::get($action, 'path', '#');

                return [
                    'label' => __t(
                        Arr::get($action, 'label_key', 'dashboard.quick_actions.default'),
                        Arr::get($action, 'fallback_label', 'Open')
                    ),
                    'icon' => Arr::get($action, 'icon', 'icon-chevron-right'),
                    'url' => url($path),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Build a read-model for the signed-in user context.
     */
    public function getUserContext(User $user): array
    {
        $roles = $user->roles->pluck('name')->toArray();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->profile->fullname_en ?? $user->profile->fullname_th ?? $user->username,
                'role' => implode(', ', $roles) ?: $user->type->name ?? __t('dashboard.labels.unknown_role', 'User'),
                'branch' => null,
            ],
            'status' => optional($user->status)->name ?? 'Active',
            'last_login' => $user->updated_at,
            'open_tasks' => [
                [
                    'label' => __t('dashboard.tasks.pending_transfers', 'Pending transfers'),
                    'count' => TransferSlip::query()
                        ->whereHas('status', function ($query) {
                            $query->where('name', 'Pending');
                        })
                        ->count(),
                    'link' => url('/menu/menu_warehouse_transfer'),
                ],
                [
                    'label' => __t('dashboard.tasks.open_stock_checks', 'Open stock checks'),
                    'count' => CheckStockReport::query()->where('closed', false)->count(),
                    'link' => url('/menu/menu_warehouse_checkstock'),
                ],
            ],
        ];
    }

    /**
     * Rebuild cache keys per section.
     */
    private function cacheKey(string $section, int $userId): string
    {
        return sprintf('dashboard:%s:user:%d', $section, $userId);
    }

    private function calculateSalesTotal(Carbon $from, Carbon $to): float
    {
        return (float) Inventory::query()
            ->where('move_type_id', InventoryService::MOVE_TYPE_STOCK_OUT)
            ->whereBetween('date_activity', [$from, $to])
            ->select(DB::raw('SUM(ABS(quantity_move) * COALESCE(NULLIF(avr_sale_price, 0), avr_buy_price)) as total'))
            ->value('total') ?? 0.0;
    }

    private function getTopMovers(int $limit = 5): array
    {
        $days = (int) config('dashboard.top_movers_days', 30);
        $from = Carbon::now()->subDays($days);

        return Inventory::query()
            ->select(
                'products.id as product_id',
                'products.sku_number',
                'products.name',
                DB::raw('SUM(ABS(inventories.quantity_move)) as quantity')
            )
            ->join('products', 'products.id', '=', 'inventories.product_id')
            ->where('inventories.move_type_id', InventoryService::MOVE_TYPE_STOCK_OUT)
            ->where('inventories.date_activity', '>=', $from)
            ->groupBy('products.id', 'products.sku_number', 'products.name')
            ->orderByDesc('quantity')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                return [
                    'product_id' => (int) $row->product_id,
                    'sku' => $row->sku_number,
                    'name' => $row->name,
                    'qty_moved' => (int) $row->quantity,
                ];
            })
            ->toArray();
    }

    private function getLowStock(int $limit = 5): array
    {
        $threshold = (int) config('dashboard.low_stock_fallback_threshold', 5);

        return WarehouseProduct::query()
            ->select(
                'warehouses_products.id',
                'warehouses_products.warehouse_id',
                'warehouses_products.product_id',
                'warehouses_products.balance',
                'warehouses.name as warehouse_name',
                'products.sku_number',
                'products.name',
                'products.minimum_quantity'
            )
            ->join('warehouses', 'warehouses.id', '=', 'warehouses_products.warehouse_id')
            ->join('products', 'products.id', '=', 'warehouses_products.product_id')
            ->whereRaw('warehouses_products.balance <= COALESCE(NULLIF(products.minimum_quantity, 0), ?)', [$threshold])
            ->orderBy('warehouses_products.balance', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($row) use ($threshold) {
                return [
                    'warehouse_id' => (int) $row->warehouse_id,
                    'warehouse_name' => $row->warehouse_name,
                    'product_id' => (int) $row->product_id,
                    'sku' => $row->sku_number,
                    'name' => $row->name,
                    'remaining' => (int) $row->balance,
                    'reorder_point' => (int) ($row->minimum_quantity ?: $threshold),
                ];
            })
            ->toArray();
    }

    private function getBranchCapacityUtilisation(int $limit = 5): array
    {
        $warning = (float) config('dashboard.branch_capacity_warning', 0.75);
        $critical = (float) config('dashboard.branch_capacity_critical', 0.9);

        $capacities = Warehouse::query()
            ->select(
                'branches.id as branch_id',
                DB::raw('COALESCE(branches.name_en, branches.name_th) as branch_name'),
                DB::raw('SUM(warehouses_products.balance) as balance'),
                DB::raw('SUM(COALESCE(NULLIF(products.maximum_quantity, 0), warehouses_products.balance)) as capacity')
            )
            ->join('branches', 'branches.id', '=', 'warehouses.branch_id')
            ->join('warehouses_products', 'warehouses_products.warehouse_id', '=', 'warehouses.id')
            ->join('products', 'products.id', '=', 'warehouses_products.product_id')
            ->groupBy('branches.id', 'branch_name')
            ->orderByDesc('balance')
            ->limit($limit)
            ->get();

        return $capacities->map(function ($row) use ($warning, $critical) {
            $capacity = (float) $row->capacity;
            $balance = (float) $row->balance;
            $ratio = $capacity > 0 ? min($balance / $capacity, 1) : 0;

            $status = 'normal';
            if ($ratio >= $critical) {
                $status = 'critical';
            } elseif ($ratio >= $warning) {
                $status = 'warning';
            }

            return [
                'branch_id' => (int) $row->branch_id,
                'branch_name' => $row->branch_name,
                'capacity_used_pct' => round($ratio * 100, 1),
                'balance' => (int) $balance,
                'capacity' => (int) $capacity,
                'status' => $status,
            ];
        })->toArray();
    }

    private function getAgingInventory(int $limit = 5): array
    {
        $agingDays = (int) config('dashboard.aging_days', 60);
        $cutoff = Carbon::now()->subDays($agingDays);

        return WarehouseProduct::query()
            ->select(
                'warehouses_products.product_id',
                'products.sku_number',
                'products.name',
                'warehouses_products.balance',
                'warehouses_products.updated_at'
            )
            ->join('products', 'products.id', '=', 'warehouses_products.product_id')
            ->where('warehouses_products.balance', '>', 0)
            ->whereDate('warehouses_products.updated_at', '<=', $cutoff)
            ->orderBy('warehouses_products.updated_at', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                return [
                    'product_id' => (int) $row->product_id,
                    'sku' => $row->sku_number,
                    'name' => $row->name,
                    'balance' => (int) $row->balance,
                    'days_in_stock' => $row->updated_at ? $row->updated_at->diffInDays(now()) : null,
                ];
            })
            ->toArray();
    }

    private function buildMonthlyBuckets(int $months): Collection
    {
        $now = Carbon::now()->startOfMonth();

        return collect(range($months - 1, 0))
            ->map(function ($index) use ($now) {
                $start = $now->copy()->subMonths($index);
                $end = $start->copy()->endOfMonth();

                return [
                    'label' => $start->format('M Y'),
                    'start' => $start,
                    'end' => $end,
                ];
            });
    }

    private function buildWeeklyBuckets(int $weeks): Collection
    {
        $now = Carbon::now()->startOfWeek();

        return collect(range($weeks - 1, 0))
            ->map(function ($index) use ($now) {
                $start = $now->copy()->subWeeks($index);
                $end = $start->copy()->endOfWeek();

                return [
                    'label' => sprintf('%s %s', __t('dashboard.time.week', 'Week'), $start->format('W')),
                    'start' => $start,
                    'end' => $end,
                ];
            });
    }

    private function buildDailyBuckets(int $days): Collection
    {
        $now = Carbon::today();

        return collect(range($days - 1, 0))
            ->map(function ($index) use ($now) {
                $start = $now->copy()->subDays($index);
                $end = $start->copy()->endOfDay();

                return [
                    'label' => $start->format('d M'),
                    'start' => $start,
                    'end' => $end,
                ];
            });
    }

    private function aggregateSeries(Collection $buckets, $query, string $dateColumn): array
    {
        return $buckets->map(function (array $bucket) use ($query, $dateColumn) {
            $value = (float) (clone $query)
                ->whereBetween($dateColumn, [$bucket['start'], $bucket['end']])
                ->value('value');

            return [
                'period' => $bucket['label'],
                'value' => round($value, 2),
            ];
        })->toArray();
    }
}
