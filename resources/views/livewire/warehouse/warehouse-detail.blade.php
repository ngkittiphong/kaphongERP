<!-- resources/views/livewire/warehouse-detail.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="flex items-center justify-center w-full"
        style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="panel-body">
            <div class="loader">
                <div class="loader-inner ball-beat">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2) Hide the Form While Loading -->
    <div wire:loading.remove>
        @if ($showAddWarehouseForm)
            @include('livewire.warehouse.warehouse-detail_addwarehouse')
        @elseif($showEditWarehouseForm && $warehouse)
            @include('livewire.warehouse.warehouse-detail_edit')
        @elseif($warehouse && $showEditWarehouseForm == false)
            @include('livewire.warehouse.warehouse-detail_tab')
        @elseif($warehouse == null)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h4>Select a warehouse to view details</h4>
                            <p class="text-muted">Choose a warehouse from the list to see its information</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(warehouseId) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will deactivate the warehouse. You can reactivate it later if needed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, deactivate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteWarehouse', {
                        warehouseId: warehouseId
                    });
                }
            });
        }

        function confirmReactivate(warehouseId) {
            Swal.fire({
                title: "Reactivate Warehouse?",
                text: "This will reactivate the warehouse and make it available for use.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, reactivate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('reactivateWarehouse', {
                        warehouseId: warehouseId
                    });
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('warehouseCreated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Create Success',
                text: data.message || 'Warehouse created successfully!',
            });
        });

        Livewire.on('warehouseUpdated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Update Success',
                text: data.message || 'Warehouse updated successfully!',
            });
        });

        Livewire.on('warehouseDeleted', data => {
            Swal.fire({
                icon: 'success',
                title: 'Deactivation Success',
                text: data.message || 'Warehouse deactivated successfully!',
            });
        });

        Livewire.on('warehouseReactivated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Reactivation Success',
                text: data.message || 'Warehouse reactivated successfully!',
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('WarehouseDetail livewire:initialized');
            
            @this.on('addWarehouse', () => {
                console.log('addWarehouse');
                setTimeout(() => {
                    // Initialize any add warehouse form functionality here
                }, 100);
            });
        });
    </script>
@endpush
