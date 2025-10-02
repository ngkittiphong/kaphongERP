<!-- resources/views/livewire/branch-detail.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="d-flex align-items-center justify-content-center w-100"
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
        @if ($showAddBranchForm)
            @include('livewire.branch.branch-detail_addbranch')
        @elseif($showEditBranchForm && $branch)
            @include('livewire.branch.branch-detail_edit')
        @elseif($branch && $showEditBranchForm == false)
            @include('livewire.branch.branch-detail_tab')
        @elseif($branch == null)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h4>{{ __t('branch.select_branch_to_view_details', 'Select a branch to view details') }}</h4>
                            <p class="text-muted">{{ __t('branch.choose_branch_from_list', 'Choose a branch from the list to see its information') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(branchId) {
            Swal.fire({
                title: "{{ __t('common.are_you_sure', 'Are you sure?') }}",
                text: "{{ __t('common.action_cannot_be_undone', 'This action cannot be undone!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __t('common.yes_delete_it', 'Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteBranch', {
                        branchId: branchId
                    });
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('branchCreated', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.create_success', 'Create Success') }}',
                text: data.message,
            });
        });

        Livewire.on('branchUpdated', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.update_success', 'Update Success') }}',
                text: data.message,
            });
        });

        Livewire.on('branchDeleted', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.delete_success', 'Delete Success') }}',
                text: data.message,
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('BranchDetail livewire:initialized');
            
            @this.on('branchSelected', () => {
                console.log('branchSelected');
                setTimeout(() => {
                    // Initialize any branch-specific functionality here
                    $('.venobox').venobox();
                }, 100);
            });

            @this.on('addBranch', () => {
                console.log('addBranch');
                setTimeout(() => {
                    // Initialize any add branch form functionality here
                }, 100);
            });
        });
    </script>
@endpush
