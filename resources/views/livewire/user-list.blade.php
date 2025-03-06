<!-- resources/views/livewire/user-list.blade.php -->

<div id="contacts" style="display:inherit">
    <div class="p-l-15 p-r-15 p-t-15 m-b-20">
        <div class="form-group has-feedback has-feedback-left">
            <button type="button" class="btn btn-sm btn-success btn-labeled"
                wire:click="$dispatch('showAddUserForm')">
                <b><i class="icon-plus3"></i></b> Add new user
            </button>
        </div>
    </div>

    <div id="contacts" x-data="userList({ users: {{ json_encode($users ?? []) }} })">
        <div class="p-3">
            <input type="text" class="form-control" x-model="search" placeholder="Search user...">
        </div>
        <ul class="media-list media-list-linked p-b-5">
            <template x-for="user in paginatedUsers()" :key="user.id">
                <li class="p-2 cursor-pointer hover:bg-blue-100"
                    wire:click="$dispatch('ProfileSelected', { userId: user.id })">
                    <div class="media-link">
                        <div class="media-left"><img src="{{ asset('assets/images/faces/face1.png') }}" class="img-circle" alt=""></div>
                            <div class="media-body">
                                <span class="media-heading" x-text="user.email"></span>
                                <span class="media-annotation">Lead UX designer</span>
                            </div>
                            <div class="media-right media-middle">
                                <span class="status-mark bg-success"></span>
                        </div>
                    </div>
                </li>
            </template>
        </ul>
        <!-- Pagination Controls -->
        <div class="flex justify-center items-center mt-4 space-x-2">
            <!-- Previous Button -->
            <button class="btn btn-sm btn-secondary"
                @click="prevPage()" 
                :disabled="currentPage === 1">
                Prev
            </button>
    
            <!-- Page Number Links -->
            <template x-for="page in totalPages" :key="page">
                <button class="btn btn-sm"
                    :class="{'bg-blue-500 text-white': currentPage === page, 'bg-gray-200': currentPage !== page}"
                    @click="goToPage(page)">
                    <span x-text="page"></span>
                </button>
            </template>
    
            <!-- Next Button -->
            <button class="btn btn-sm btn-secondary"
                @click="nextPage()"
                :disabled="currentPage === totalPages">
                Next
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function userList(data) {
        return {
            search: '',
            users: data.users || [],
            currentPage: 1,
            perPage: 10, // Show 10 users per page

            // 🔹 Filter users based on search
            filteredUsers() {
                if (!this.search) return this.users;
                return this.users.filter(user =>
                    user.username.toLowerCase().includes(this.search.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            // 🔹 Paginate the filtered users
            paginatedUsers() {
                let start = (this.currentPage - 1) * this.perPage;
                let end = start + this.perPage;
                return this.filteredUsers().slice(start, end);
            },

            // 🔹 Total pages calculation
            get totalPages() {
                return Math.ceil(this.filteredUsers().length / this.perPage) || 1;
            },

            // 🔹 Navigate to a specific page
            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },

            // 🔹 Go to the previous page
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },

            // 🔹 Go to the next page
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            }
        };
    }
</script>
@endpush
