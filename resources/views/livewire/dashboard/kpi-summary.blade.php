<div class="row">
    @php
        $cards = [
            [
                'key' => 'sales_today',
                'label' => __t('dashboard.kpi.sales_today', 'Sales Today'),
                'value' => isset($totals['sales_today']) ? currency($totals['sales_today']) : currency(0),
                'icon' => 'icon-cash3',
                'tone' => 'bg-primary',
            ],
            [
                'key' => 'sales_mtd',
                'label' => __t('dashboard.kpi.sales_mtd', 'Sales MTD'),
                'value' => isset($totals['sales_mtd']) ? currency($totals['sales_mtd']) : currency(0),
                'icon' => 'icon-stats-growth',
                'tone' => 'bg-success',
            ],
            [
                'key' => 'pending_transfer_slips',
                'label' => __t('dashboard.kpi.pending_transfers', 'Pending Transfers'),
                'value' => $totals['pending_transfer_slips'] ?? 0,
                'icon' => 'icon-truck',
                'tone' => 'bg-warning',
            ],
            [
                'key' => 'low_stock_skus',
                'label' => __t('dashboard.kpi.low_stock', 'Low-stock SKUs'),
                'value' => $totals['low_stock_skus'] ?? 0,
                'icon' => 'icon-alert',
                'tone' => 'bg-danger',
            ],
            [
                'key' => 'active_branches',
                'label' => __t('dashboard.kpi.active_branches', 'Active Branches'),
                'value' => $totals['active_branches'] ?? 0,
                'icon' => 'icon-office',
                'tone' => 'bg-slate-600',
            ],
        ];
    @endphp

    @foreach ($cards as $card)
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="panel panel-flat no-border {{ $card['tone'] }} text-center text-white">
                <div class="panel-body">
                    <div class="text-uppercase small text-muted">{{ $card['label'] }}</div>
                    <div class="text-size-huge text-semibold m-t-10">
                        @if (is_numeric($card['value']))
                            {{ number_format($card['value']) }}
                        @else
                            {{ $card['value'] }}
                        @endif
                    </div>
                    <div class="m-t-10">
                        <i class="{{ $card['icon'] }} text-white text-size-large"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($lastUpdatedHuman)
        <div class="col-xs-12">
            <p class="text-muted text-right text-small m-b-0">
                {{ __t('dashboard.labels.updated', 'Updated') }} {{ $lastUpdatedHuman }}
            </p>
        </div>
    @endif
</div>
