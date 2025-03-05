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
        <input type="text" class="form-control input-lg"
            wire:model.debounce.100ms="search"
            wire:keydown.debounce.100ms="searchUsers"
            placeholder="Search user...">
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
            wire:click="$dispatch('ProfileSelected', { userId: {{ $user->id }} })">
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

        <div class="pagination-info">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
            (Page {{ $users->currentPage() }} of {{ $users->lastPage() }})
        </div>
        <div class="pagination-links">
            @if ($users->lastPage() > 1)
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <span class="disabled">&laquo;</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" wire:click.prevent="previousPage" rel="prev">&laquo;</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 3), min($users->lastPage(), $users->currentPage() + 3)) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" wire:click.prevent="nextPage" rel="next">&raquo;</a>
                    @else
                        <span class="disabled">&raquo;</span>
                    @endif
                </div>
            @endif
        </div>
    </ul>
</div>
