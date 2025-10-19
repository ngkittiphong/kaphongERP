<div class="panel panel-flat no-border">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="icon-cube3 position-left"></i>{{ __t('dashboard.sections.inventory_health', 'Inventory Health') }}
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h6 class="text-uppercase text-semibold">{{ __t('dashboard.subsections.top_movers', 'Top Movers (Last 30 days)') }}</h6>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __t('dashboard.labels.product', 'Product') }}</th>
                                <th class="text-right">{{ __t('dashboard.labels.quantity', 'Qty') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topMovers as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="text-semibold">{{ $item['name'] }}</div>
                                        <div class="text-muted small">{{ $item['sku'] }}</div>
                                    </td>
                                    <td class="text-right text-semibold">{{ number_format($item['qty_moved']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __t('dashboard.labels.no_movement', 'No movement recorded for this period.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <h6 class="text-uppercase text-semibold">{{ __t('dashboard.subsections.low_stock', 'Low Stock Watchlist') }}</h6>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>{{ __t('dashboard.labels.product', 'Product') }}</th>
                                <th>{{ __t('dashboard.labels.warehouse', 'Warehouse') }}</th>
                                <th class="text-right">{{ __t('dashboard.labels.remaining', 'Remaining') }}</th>
                                <th class="text-right">{{ __t('dashboard.labels.reorder_point', 'Reorder Pt.') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStock as $item)
                                <tr>
                                    <td>
                                        <div class="text-semibold">{{ $item['name'] }}</div>
                                        <div class="text-muted small">{{ $item['sku'] }}</div>
                                    </td>
                                    <td>{{ $item['warehouse_name'] }}</td>
                                    <td class="text-right text-danger text-semibold">{{ number_format($item['remaining']) }}</td>
                                    <td class="text-right text-muted">{{ number_format($item['reorder_point']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        {{ __t('dashboard.labels.no_low_stock', 'All items are above reorder points.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row m-t-20">
            <div class="col-md-6 col-sm-12">
                <h6 class="text-uppercase text-semibold">{{ __t('dashboard.subsections.branch_capacity', 'Branch Capacity') }}</h6>
                <ul class="list-feed">
                    @forelse ($branchCapacity as $branch)
                        <li class="border-{{ $branch['status'] === 'critical' ? 'danger' : ($branch['status'] === 'warning' ? 'warning' : 'success') }}">
                            <div class="text-semibold">{{ $branch['branch_name'] }}</div>
                            <div class="text-muted small">
                                {{ __t('dashboard.labels.utilisation', 'Utilisation') }}: {{ $branch['capacity_used_pct'] }}%
                                · {{ __t('dashboard.labels.balance', 'Balance') }} {{ number_format($branch['balance']) }}
                            </div>
                        </li>
                    @empty
                        <li class="text-muted">{{ __t('dashboard.labels.no_capacity_data', 'No warehouse utilisation data available.') }}</li>
                    @endforelse
                </ul>
            </div>
            <div class="col-md-6 col-sm-12">
                <h6 class="text-uppercase text-semibold">{{ __t('dashboard.subsections.aging_stock', 'Aging Stock') }}</h6>
                <ul class="media-list media-list-linked">
                    @forelse ($aging as $item)
                        <li class="media">
                            <div class="media-left">
                                <span class="btn btn-flat border-danger text-danger btn-xs">
                                    {{ $item['days_in_stock'] ?? '—' }} {{ __t('dashboard.labels.days', 'days') }}
                                </span>
                            </div>
                            <div class="media-body">
                                <div class="media-heading text-semibold">{{ $item['name'] }}</div>
                                <div class="text-muted small">
                                    {{ $item['sku'] }} · {{ __t('dashboard.labels.on_hand', 'On hand') }} {{ number_format($item['balance']) }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-muted p-10">
                            {{ __t('dashboard.labels.no_aging_stock', 'No aging stock detected.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
