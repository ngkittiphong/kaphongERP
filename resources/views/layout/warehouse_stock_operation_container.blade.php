	<!--Page Container-->
<section class="main-container">					
		
    
    <div class="content-wrapper">
        <!-- Page header -->
        <div class="page-header page-header-default">
            <div class="page-header-content">
                <div class="page-title">
                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Warehouse</span> - Stock Operations</h4>
                </div>
    
                <div class="heading-elements">
                    <div class="heading-btn-group">
                        <a href="/menu/menu_warehouse" class="btn btn-link btn-float text-size-small has-text">
                            <i class="icon-bars-alt text-primary"></i>
                            <span>Warehouse</span>
                        </a>
                        <a href="/menu/menu_warehouse_checkstock" class="btn btn-link btn-float text-size-small has-text">
                            <i class="icon-calculator text-primary"></i>
                            <span>Check Stock</span>
                        </a>
                        <a href="/menu/menu_warehouse_transfer" class="btn btn-link btn-float text-size-small has-text">
                            <i class="icon-transmission text-primary"></i>
                            <span>Transfer</span>
                        </a>
                    </div>
                </div>
            </div>
    
            <div class="breadcrumb-line">
                <ul class="breadcrumb">
                    <li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
                    <li><a href="/menu/menu_warehouse">Warehouse</a></li>
                    <li class="active">Stock Operations</li>
                </ul>
    
                <ul class="breadcrumb-elements">
                    <li><a href="#"><i class="icon-comment-discussion position-left"></i> Support</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-gear position-left"></i>
                            Settings
                            <span class="caret"></span>
                        </a>
    
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="/setting/company_profile"><i class="icon-user-lock"></i> Account security</a></li>
                            <li><a href="#"><i class="icon-statistics"></i> Analytics</a></li>
                            <li><a href="#"><i class="icon-accessibility"></i> Accessibility</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="icon-gear"></i> All settings</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /page header -->
    
        <!-- Content area -->
        <div class="content">
            <!-- Info alert -->
            <div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <h6 class="alert-heading text-semibold">Stock Operations Management</h6>
                Manage your inventory with stock-in, stock-out, and adjustment operations. 
                All operations are tracked and logged for audit purposes.
            </div>
    
            <!-- Main content -->
            <div class="row">
                <div class="col-md-12">
                    @livewire('warehouse.warehouse-stock-operations')
                </div>
            </div>
    
            <!-- Help Panel -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                                <i class="icon-info22"></i>
                                Operation Guide
                            </h6>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse" class="rotate-180"></a></li>
                                </ul>
                            </div>
                        </div>
    
                        <div class="panel-body" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="text-semibold text-success">
                                        <i class="icon-plus-circle2"></i> Stock In
                                    </h6>
                                    <p class="text-muted">
                                        Add inventory to your warehouse. Use this for:
                                    </p>
                                    <ul class="text-muted">
                                        <li>New purchases</li>
                                        <li>Returns from customers</li>
                                        <li>Production outputs</li>
                                        <li>Initial stock setup</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-semibold text-danger">
                                        <i class="icon-minus-circle2"></i> Stock Out
                                    </h6>
                                    <p class="text-muted">
                                        Remove inventory from your warehouse. Use this for:
                                    </p>
                                    <ul class="text-muted">
                                        <li>Sales to customers</li>
                                        <li>Returns to suppliers</li>
                                        <li>Internal consumption</li>
                                        <li>Damaged/lost items</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-semibold text-warning">
                                        <i class="icon-wrench"></i> Stock Adjustment
                                    </h6>
                                    <p class="text-muted">
                                        Correct inventory discrepancies. Use this for:
                                    </p>
                                    <ul class="text-muted">
                                        <li>Physical count corrections</li>
                                        <li>System error corrections</li>
                                        <li>Shrinkage adjustments</li>
                                        <li>Quality control rejects</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /content area -->
    </div>
                                                    
@push('styles')
<style>
    .transfer-status-indicator {
        position: relative;
        display: inline-block;
    }
    
    .status-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .selection-indicator {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 14px;
        height: 14px;
        background: #007bff;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 8px;
        box-shadow: 0 2px 4px rgba(0,123,255,0.3);
        animation: pulse 2s infinite;
    }
    
    .transfer-row.selected .selection-indicator {
        display: flex;
    }
    
    .transfer-row.selected .status-circle {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .transfer-row:hover .status-circle {
        transform: scale(1.05);
    }
    
    .transfer-row:hover .selection-indicator {
        display: flex;
        opacity: 0.7;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .transfer-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .transfer-row:hover {
        border-left-color: #007bff;
        background-color: #f8f9fa !important;
    }
    
    .transfer-row.selected {
        border-left-color: #28a745;
        background-color: #e8f5e8 !important;
    }
    
    /* Compact list styling */
    .transfer-row td {
        padding: 4px 6px;
    }
    
    .media-body {
        line-height: 1.2;
    }
    
    .media-heading {
        margin-bottom: 2px;
    }
    
    .text-size-large {
        margin-bottom: 1px;
    }
    
    /* Reduce spacing in list items */
    .lease-order-row {
        border-bottom: 1px solid #f0f0f0;
        min-height: 60px;
    }
    
    .lease-order-row:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Optimize sidebar content */
    .sidebar-content {
        padding: 0;
    }
    
    /* Compact table styling */
    .table-condensed > thead > tr > th,
    .table-condensed > tbody > tr > td {
        padding: 4px 6px;
    }
    
    /* Sticky header for better scrolling */
    .table thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-bottom: 2px solid #ddd;
    }
    
    /* Modal styling for transfer form */
    .modal.show {
        display: block !important;
    }
    
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush
    
  
    
</section>
<!--/Page Container-->