<div role="tabpanel" class="tab-pane active fadeIn" id="menu">
    <ul class="sidebar-accordion">
    
        <li class="list-title">{{ __t('menu.main', 'Main') }}</li>
        @can('menu.dashboard')
            <li>
                <a href="/"><i class="icon-display4"></i><span class="list-label"> {{ __t('menu.dashboards', 'Dashboards') }}</span></a>
                <ul>
                    <li><a href="/">{{ __t('menu.main_dashboard', 'Main dashboard') }}</a></li>								
                </ul>
            </li>
        @endcan
        
        @can('menu.products')
            <li class="list-title">{{ __t('menu.products', 'Products') }}</li>						
            <li>
                <a href=""><i class="icon-alignment-unalign"></i> <span>{{ __t('menu.products', 'Products') }}</span></a>
                <ul>							
                    <li><a href="/menu/menu_product">{{ __t('menu.products', 'Products') }}</a></li>
                    <li><a href="/menu/menu_category">{{ __t('menu.category', 'Category') }}</a></li>
                    <li><a href="/menu/menu_transfer">{{ __t('menu.transfer', 'Transfer') }}</a></li>																			
                </ul>
            </li>
        @endcan
        
        @can('menu.user_management')
            <li class="list-title">{{ __t('menu.user_management', 'User Management') }}</li>	
            <li><a href="/menu/menu_user"><i class="icon-users2"></i> <span>{{ __t('menu.users_list', 'Users list') }}</span></a></li>
            @can('menu.user_permissions')
                <li><a href="/menu/menu_permissions"><i class="icon-key"></i> <span>{{ __t('menu.permissions', 'Permissions') }}</span></a></li>
            @endcan
        @endcan


        @can('menu.pos')
            <li class="list-title">{{ __t('menu.sell', 'Sell') }}</li>	
            <li><a href="https://pos.ultimatefosters.com/pos/create"><i class="icon-versions"></i> <span>{{ __t('menu.pos', 'POS') }}</span></a></li>
        @endcan

        @can('menu.branch')
            <li class="list-title">{{ __t('menu.branch', 'Branch') }}</li>	
            <li><a href="/menu/menu_branch"><i class="icon-briefcase"></i> <span>{{ __t('menu.branch', 'Branch') }}</span></a></li>
        @endcan
        
        @can('menu.warehouse')
            <li class="list-title">{{ __t('menu.warehouse', 'Warehouse') }}</li>	
            <li>
                <a href=""><i class="icon-briefcase"></i> <span>{{ __t('menu.warehouse', 'Warehouse') }}</span></a>
                <ul>							
                    <li><a href="/menu/menu_warehouse">{{ __t('menu.warehouse', 'Warehouse') }}</a></li>
                    <li><a href="/menu/menu_warehouse_checkstock">{{ __t('menu.check_stock', 'Check Stock') }}</a></li>
                    <li><a href="/menu/menu_warehouse_transfer">{{ __t('menu.transfer', 'Transfer') }}</a></li>
                    <li><a href="/menu/menu_warehouse_stock">{{ __t('menu.stock_operations', 'Stock Operations') }}</a></li>																			
                </ul>
            </li>
        @endcan										
    </ul>
</div>
