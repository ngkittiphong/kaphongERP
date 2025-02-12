<!-- resources/views/livewire/user-profile.blade.php -->
<div class="row p-l-10 p-r-10">
    @if ($user)
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <img src="{{ asset('assets/images/faces/face1.png') }}" class="img-responsive img-circle user-avatar" alt="">
            <h2 class="no-margin-bottom m-t-10">{{ $user->profile->fullname_en }}</h2>
            <div>Company Secretary</div>
        </div>
    </div>
    <div class="col-md-8 col-xs-12">
        <div class="panel panel-flat">
            <div class="panel-heading no-padding-bottom">
                <h5 class="panel-title">User profile</h5>						
            </div>
            
            <div class="list-group list-group-lg list-group-borderless">												
                <a href="people.htm#" class="list-group-item p-l-20">
                    <i class="icon-envelop3"></i> {{ $user->email }}
                </a>
                <a href="people.htm#" class="list-group-item p-l-20">
                    <i class="icon-phone2"></i> {{ $user->profile->phone }}
                </a>
                <a href="people.htm#" class="list-group-item p-l-20">
                    <i class="icon-location4"></i> {{ $user->profile->address }}
                </a>
            </div>									
        </div>
    </div>
    @endif
</div>




{{-- 

<div class="w-3/4 p-6">
    @if ($user)
        <h2 class="text-xl font-bold">{{ $user->email }}</h2>
        <div class="mt-4">
            <label class="block">Fullname</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->fullname_en }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Nickname</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->nickname }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Description</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->description }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Card ID No</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->card_id_no }}" readonly>
        </div>
        <div class="flex space-x-4 mt-6">
            <button class="bg-green-500 text-white px-4 py-2 rounded">Edit</button>
            <button class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
        </div>
    @else
        <p>Select a user to view details</p>
    @endif
</div> --}}
