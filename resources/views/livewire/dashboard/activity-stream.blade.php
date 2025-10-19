<div class="panel panel-flat no-border">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="icon-feed position-left"></i>{{ __t('dashboard.sections.activity', 'Latest Activity') }}
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h6 class="text-uppercase text-muted text-semibold">
                    {{ __t('dashboard.activity.transfers', 'Transfer Slips') }}
                </h6>
                <ul class="media-list media-list-linked">
                    @forelse ($transfers as $item)
                        <li class="media">
                            <div class="media-body">
                                <div class="media-heading text-semibold">
                                    #{{ $item['number'] }}
                                </div>
                                <div class="text-muted small">
                                    {{ $item['origin'] }} → {{ $item['destination'] }}
                                </div>
                                <div class="text-small">
                                    <span class="label label-primary">{{ $item['status'] }}</span>
                                    <span class="text-muted">{{ $item['updated_at'] }}</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="media text-muted">
                            {{ __t('dashboard.labels.no_recent_transfers', 'No recent transfer activity.') }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-4 col-sm-12">
                <h6 class="text-uppercase text-muted text-semibold">
                    {{ __t('dashboard.activity.stock_checks', 'Stock Checks') }}
                </h6>
                <ul class="media-list media-list-linked">
                    @forelse ($stockChecks as $item)
                        <li class="media">
                            <div class="media-body">
                                <div class="media-heading text-semibold">{{ $item['warehouse'] }}</div>
                                <div class="text-small">
                                    <span class="label label-info">{{ $item['status'] }}</span>
                                    <span class="text-muted">{{ $item['performed_at'] }}</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="media text-muted">
                            {{ __t('dashboard.labels.no_stock_checks', 'No recent stock checks.') }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-4 col-sm-12">
                <h6 class="text-uppercase text-muted text-semibold">
                    {{ __t('dashboard.activity.user_events', 'User Updates') }}
                </h6>
                <ul class="media-list media-list-linked">
                    @forelse ($userEvents as $item)
                        <li class="media">
                            <div class="media-body">
                                <div class="media-heading text-semibold">{{ $item['name'] }}</div>
                                <div class="text-muted small">{{ $item['username'] }} · {{ $item['status'] }}</div>
                                <div class="text-small text-muted">{{ $item['occurred_at'] }}</div>
                            </div>
                        </li>
                    @empty
                        <li class="media text-muted">
                            {{ __t('dashboard.labels.no_user_events', 'No recent user changes.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
