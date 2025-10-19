<div class="panel panel-flat no-border bg-slate-light">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-8">
                <h5 class="text-uppercase text-semibold">
                    {{ __t('dashboard.sections.welcome', 'Welcome back') }},
                    {{ $userInfo['name'] ?? __t('dashboard.labels.colleague', 'colleague') }}
                </h5>
                <div class="text-muted">
                    {{ __t('dashboard.labels.role', 'Role') }}:
                    <span class="text-semibold">{{ $userInfo['role'] ?? __t('dashboard.labels.unknown_role', 'User') }}</span>
                </div>
                @if (! empty($userInfo['branch']))
                    <div class="text-muted">
                        {{ __t('dashboard.labels.branch', 'Branch') }}:
                        <span class="text-semibold">{{ $userInfo['branch'] }}</span>
                    </div>
                @endif
                @if ($status)
                    <div class="text-muted">
                        {{ __t('dashboard.labels.status', 'Status') }}:
                        <span class="label label-success">{{ $status }}</span>
                    </div>
                @endif
                @if ($lastLoginHuman)
                    <div class="text-muted">
                        {{ __t('dashboard.labels.last_active', 'Last update') }}: {{ $lastLoginHuman }}
                    </div>
                @endif
            </div>
            <div class="col-sm-4 text-right">
                <h6 class="text-uppercase text-muted">{{ __t('dashboard.sections.quick_actions', 'Quick Actions') }}</h6>
                @forelse ($quickActions as $action)
                    <a href="{{ $action['url'] }}"
                       class="btn btn-primary btn-sm btn-block m-b-5 text-left">
                        <i class="{{ $action['icon'] }} position-left"></i>{{ $action['label'] }}
                    </a>
                @empty
                    <p class="text-muted small">{{ __t('dashboard.labels.no_actions', 'No shortcuts available for your role.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="panel-body border-top">
        <h6 class="text-uppercase text-muted text-semibold">
            {{ __t('dashboard.sections.open_tasks', 'Open Tasks') }}
        </h6>
        <div class="row">
            @forelse ($openTasks as $task)
                <div class="col-sm-6">
                    <div class="media">
                        <div class="media-left">
                            <span class="btn btn-flat border-primary text-primary btn-xs">
                                {{ $task['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="media-body">
                            <div class="media-heading text-semibold">{{ $task['label'] ?? '' }}</div>
                            @if (! empty($task['link']))
                                <a href="{{ $task['link'] }}" class="text-small text-primary">
                                    {{ __t('dashboard.labels.review', 'Review') }} <i class="icon-arrow-right13"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-sm-12 text-muted">
                    {{ __t('dashboard.labels.no_tasks', 'Everything is up to date.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
