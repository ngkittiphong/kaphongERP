<div class="row">
    <!-- Stock Operations Panel -->
    <div class="col-md-8">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Stock Operations</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">
                <!-- Tab Navigation -->
                <div class="tabbable">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="{{ $activeTab === 'stock-in' ? 'active' : '' }}">
                            <a href="#tab-stock-in" data-toggle="tab" wire:click="setActiveTab('stock-in')" aria-expanded="{{ $activeTab === 'stock-in' ? 'true' : 'false' }}">
                                <i class="icon-plus-circle2 position-left"></i>
                                Stock In
                            </a>
                        </li>
                        <li class="{{ $activeTab === 'stock-out' ? 'active' : '' }}">
                            <a href="#tab-stock-out" data-toggle="tab" wire:click="setActiveTab('stock-out')" aria-expanded="{{ $activeTab === 'stock-out' ? 'true' : 'false' }}">
                                <i class="icon-minus-circle2 position-left"></i>
                                Stock Out
                            </a>
                        </li>
                        <li class="{{ $activeTab === 'adjustment' ? 'active' : '' }}">
                            <a href="#tab-adjustment" data-toggle="tab" wire:click="setActiveTab('adjustment')" aria-expanded="{{ $activeTab === 'adjustment' ? 'true' : 'false' }}">
                                <i class="icon-wrench position-left"></i>
                                Adjustment
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Stock In Tab -->
                        <div class="tab-pane {{ $activeTab === 'stock-in' ? 'active' : '' }}" id="tab-stock-in">
                            @livewire('warehouse.warehouse-stock-in-form')
                        </div>

                        <!-- Stock Out Tab -->
                        <div class="tab-pane {{ $activeTab === 'stock-out' ? 'active' : '' }}" id="tab-stock-out">
                            @livewire('warehouse.warehouse-stock-out-form')
                        </div>

                        <!-- Stock Adjustment Tab -->
                        <div class="tab-pane {{ $activeTab === 'adjustment' ? 'active' : '' }}" id="tab-adjustment">
                            @livewire('warehouse.warehouse-stock-adjustment-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Panel -->
    <div class="col-md-4">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">
                    Recent Transactions
                    <small class="display-block">Latest stock movements</small>
                </h6>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li>
                            <a wire:click="toggleRecentTransactions" class="btn btn-link btn-xs">
                                <i class="{{ $showRecentTransactions ? 'icon-eye-blocked' : 'icon-eye' }}"></i>
                            </a>
                        </li>
                        <li><a wire:click="refreshRecentTransactions" class="btn btn-link btn-xs"><i class="icon-rotate-cw3"></i></a></li>
                    </ul>
                </div>
            </div>

            @if($showRecentTransactions)
            <div class="panel-body">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    @if(count($recentTransactions) > 0)
                        @foreach($recentTransactions as $transaction)
                            @php
                                $transactionInfo = $this->getFormattedTransactionType($transaction->move_type_id);
                            @endphp
                            <div class="media border-bottom pb-2 mb-2">
                                <div class="media-left">
                                    <span class="btn btn-{{ $transactionInfo['class'] }} btn-rounded btn-xs">
                                        <i class="{{ $transactionInfo['icon'] }}"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading text-semibold text-size-small">
                                        {{ $transactionInfo['type'] }}
                                    </div>
                                    <div class="text-size-small text-muted">
                                        <strong>{{ $transaction->product->name ?? 'Unknown Product' }}</strong>
                                        <br>
                                        Qty: {{ number_format($transaction->quantity_move) }} {{ $transaction->unit_name }}
                                        <br>
                                        Warehouse: {{ $transaction->warehouse->name ?? 'Unknown' }}
                                        <br>
                                        <small>{{ $transaction->date_activity->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="icon-info22 text-size-large"></i>
                            <div class="mt-2">No recent transactions</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh recent transactions every 30 seconds
    setInterval(function() {
        @this.call('refreshRecentTransactions');
    }, 30000);
</script>
@endpush
