<!-- resources/views/livewire/user-profile.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    @if($showAddUserForm)
        <div class="col-md-4 col-xs-12">
            <div
            class="slim"
            data-size="300,300"
            data-ratio="1:1"
            data-shape="circle"
            data-instant-edit="true"
            style="width: 150px; height: 150px;"
        >
            <img src="{{ asset('assets/images/faces/face1.png') }}" alt="Default Icon" />

            <input type="file" name="slim" accept="image/jpeg, image/png, image/gif" />
        </div>
        <!-- 2) Hide the Form While Loading -->
        @push('scripts')
            <script src="{{ asset('slim/js/slim.kickstart.min.js') }}"></script>
        @endpush
    @else
    <div class="col-md-4 col-xs-12">
        <div
        class="slim"
        data-size="300,300"
        data-ratio="1:1"
        data-shape="circle"
        data-instant-edit="true"
        style="width: 150px; height: 150px;"
    >
        <img src="{{ asset('assets/images/faces/face1.png') }}" alt="Default Icon" />

        <input type="file" name="slim" accept="image/jpeg, image/png, image/gif" />
    </div>
    <!-- 2) Hide the Form While Loading -->
    @push('scripts')
        <script src="{{ asset('slim/js/slim.kickstart.min.js') }}"></script>
    @endpush
    @endif
 
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

@endpush