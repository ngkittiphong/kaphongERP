<div class="table-responsive">
    <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
        <thead>
            <tr>
                <th scope="col">{{ __t('menu.users', 'Users') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $l_user): ?>
            <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                wire:click="$dispatch('ProfileSelected', { userId: {{ $l_user->id }} })">
                <td>
                    <div class="media-left">
                        <img src="{{ $l_user->profile && $l_user->profile->avatar ? $l_user->profile->avatar : asset('assets/images/faces/face_default.png') }}"
                            class="img-circle" alt="{{ $l_user->username }}'s Avatar">
                    </div>

                    <div class="media-body">
                        <span class="media-heading text-size-extralarge text-dark">
                            <?= $l_user->profile?->fullname_th ?> ({{ $l_user->profile?->nickname }})
                        </span>

                        <span class=" text-size-extralarge text-dark">
                            <?= $l_user->email ?>
                        </span>
                    </div>

                    <div class="media-right media-middle">
                        <span class="status-mark bg-{{ $l_user->status?->color }}" placeholder="sss"></span>
                    </div>
                </td>
                <!--                            <td>
                        <div class="media-right media-middle">
                            <span class="status-mark bg-grey-lighter <?= $l_user->status?->color ?>"><?= $l_user->status?->name ?></span>
                        </div>
                    </td>-->
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<x-datatable-scripts listUpdatedEvent="userListUpdated" />
