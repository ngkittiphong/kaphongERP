<!-- resources/views/livewire/user-list.blade.php -->
<div id="contacts" style="display:inherit">
    <div class="p-l-15 p-r-15 p-t-15 m-b-20">
        <div class="row">
        <div class="form-group has-feedback has-feedback-left">
            <button type="button" class="btn btn-sm btn-success btn-labeled"
                wire:click="$dispatch('showAddUserForm')">
                <b><i class="icon-plus3"></i></b> Add new user
            </button>
        </div>
        <div class="row">
            <input type="text" class="form-control input-lg" placeholder="Search user...">
                <div class="form-control-feedback">
                    <i class="icon-search4"></i>
                </div>
            </div>
        </div>
    </div>
    <ul class="media-list media-list-linked p-b-5">
        @foreach ($users as $user)
        {{-- <li class="media"> --}}
        <li class="p-2 cursor-pointer hover:bg-blue-100 {{ $selectedUserId == $user->id ? 'bg-blue-200' : '' }}"
            wire:click="selectUser({{ $user->id }})">
            <div class="media-link">
                <div class="media-left"><img src="{{ asset('assets/images/faces/face1.png') }}" class="img-circle" alt=""></div>
                <div class="media-body">
                    <span class="media-heading">{{ $user->email }}</span>
                    <span class="media-annotation">Lead UX designer</span>
                </div>
                <div class="media-right media-middle">
                    <span class="status-mark bg-success"></span>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>

{{-- <div class="w-1/4 border-r p-4">
    <h2 class="text-blue-600 font-bold">Users</h2>
    <ul>
        @foreach ($users as $user)
            <li class="p-2 cursor-pointer hover:bg-blue-100 {{ $selectedUserId == $user->id ? 'bg-blue-200' : '' }}"
                wire:click="selectUser({{ $user->id }})">
                {{ $user->email }}
            </li>
        @endforeach
    </ul>
</div> --}}
