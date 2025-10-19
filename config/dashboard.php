<?php

return [
    'cache_ttl' => env('DASHBOARD_CACHE_TTL', 300),
    'top_movers_days' => env('DASHBOARD_TOP_MOVERS_DAYS', 30),
    'aging_days' => env('DASHBOARD_AGING_DAYS', 60),
    'low_stock_fallback_threshold' => env('DASHBOARD_LOW_STOCK_THRESHOLD', 5),
    'branch_capacity_warning' => (float) env('DASHBOARD_CAPACITY_WARNING', 0.75),
    'branch_capacity_critical' => (float) env('DASHBOARD_CAPACITY_CRITICAL', 0.9),
    'activity_limit' => env('DASHBOARD_ACTIVITY_LIMIT', 6),
    'quick_actions' => [
        [
            'label_key' => 'dashboard.quick_actions.create_transfer',
            'fallback_label' => 'Create Transfer Slip',
            'icon' => 'icon-truck',
            'path' => '/menu/menu_warehouse_transfer',
            'permission' => 'menu.warehouse',
        ],
        [
            'label_key' => 'dashboard.quick_actions.adjust_stock',
            'fallback_label' => 'Adjust Stock',
            'icon' => 'icon-database',
            'path' => '/menu/menu_warehouse_stock',
            'permission' => 'menu.warehouse',
        ],
        [
            'label_key' => 'dashboard.quick_actions.invite_user',
            'fallback_label' => 'Invite User',
            'icon' => 'icon-user-plus',
            'path' => '/menu/menu_user',
            'permission' => 'menu.user_management',
        ],
    ],
];
