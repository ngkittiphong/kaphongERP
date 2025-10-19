<div class="panel panel-flat no-border">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="icon-bell3 position-left"></i>{{ __t('dashboard.sections.alerts', 'Action Alerts') }}
        </h4>
    </div>
    <div class="panel-body">
        <ul class="media-list">
            @forelse ($alerts as $alert)
                @php
                    $badgeClasses = [
                        'danger' => 'label-danger',
                        'warning' => 'label-warning',
                        'info' => 'label-info',
                        'success' => 'label-success',
                    ];
                    $badgeClass = $badgeClasses[$alert['severity'] ?? 'info'] ?? 'label-default';
                @endphp
                <li class="media">
                    <div class="media-left">
                        <span class="label {{ $badgeClass }} label-rounded label-icon">
                            <i class="icon-warning2"></i>
                        </span>
                    </div>
                    <div class="media-body">
                        <div class="media-heading text-semibold">{{ $alert['label'] ?? '' }}</div>
                        <div class="text-muted small">{{ $alert['details'] ?? '' }}</div>
                        @if (! empty($alert['link']))
                            <div class="m-t-5">
                                <a href="{{ $alert['link'] }}" class="text-primary text-small">
                                    {{ __t('dashboard.labels.view', 'Open') }} <i class="icon-arrow-right13 position-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <li class="media text-muted">
                    {{ __t('dashboard.labels.no_alerts', 'Nothing needs your attention right now.') }}
                </li>
            @endforelse
        </ul>
    </div>
</div>
