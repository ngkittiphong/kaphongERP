<!-- resources/views/livewire/user-profile.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="flex items-center justify-center w-full" style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
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
        @if($showAddUserForm)
            @include('livewire.user-profile_adduser')
        @elseif($user && $user->profile && $showEditProfileForm==false)
            @include('livewire.user-profile_show_profile')
        @elseif($showEditProfileForm && $user)
            @include('livewire.user-profile_edit_userprofile')
        @elseif($user && $user->profile==null)
            {{-- <div class="form-group has-feedback has-feedback-left">
                <button type="button" class="btn btn-sm btn-success btn-labeled"
                    wire:click="$dispatch('showEditProfileForm')">
                    <b><i class="icon-plus3"></i></b> Add User Profile
                </button>
            </div> --}}
        @endif
    </div>
</div>

@push('styles')
<!-- Include Slim CSS -->
<link rel="stylesheet" href="{{ asset('slim/css/slim.min.css') }}">
@endpush

@push('scripts')
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteUser', { userId: userId });
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    Livewire.on('userCreated', data => {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: data.message,
        });
    });
</script>

<!-- Include Slim JS -->
<script src="{{ asset('slim/js/slim.kickstart.min.js') }}"></script>
@endpush