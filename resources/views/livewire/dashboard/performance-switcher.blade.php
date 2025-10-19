<div class="panel panel-flat no-border">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="panel-title">
                    <i class="icon-stats-bars position-left"></i>{{ __t('dashboard.sections.performance', 'Performance Overview') }}
                </h4>
            </div>
            <div class="col-sm-6 text-right">
                <div class="btn-group btn-group-sm" role="group">
                    @foreach ($timeframes as $option)
                        @php
                            $labels = [
                                'daily' => __t('dashboard.time.daily', 'Daily'),
                                'weekly' => __t('dashboard.time.weekly', 'Weekly'),
                                'monthly' => __t('dashboard.time.monthly', 'Monthly'),
                            ];
                        @endphp
                        <button
                            type="button"
                            class="btn btn-default {{ $timeframe === $option ? 'active' : '' }}"
                            wire:click="setTimeframe('{{ $option }}')"
                        >
                            {{ $labels[$option] ?? ucfirst($option) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <ul class="list-feed list-feed-square">
                    @foreach ($series as $item)
                        <li>
                            <span class="label label-primary" style="background-color: {{ $item['color'] }}"></span>
                            <span class="text-semibold">{{ $item['label'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>{{ __t('dashboard.labels.period', 'Period') }}</th>
                                <th class="text-right">{{ __t('dashboard.performance.sales', 'Sales') }}</th>
                                <th class="text-right">{{ __t('dashboard.performance.transfers', 'Transfer Requests') }}</th>
                                <th class="text-right">{{ __t('dashboard.performance.adjustments', 'Adjustments') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tableRows as $row)
                                <tr>
                                    <td>{{ $row['period'] }}</td>
                                    <td class="text-right text-semibold">{{ currency($row['sales'] ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($row['transfers'] ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($row['adjustments'] ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        {{ __t('dashboard.labels.no_performance_data', 'No performance data available.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
