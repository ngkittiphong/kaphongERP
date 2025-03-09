        
<div class="table-responsive">


            <table class="table datatable-reorder-state-saving datatable-fixed-left">
                <thead>
                    <tr>
                        <th scope="col"><?= __('Users') ?></th>
                        <!--<th scope="col"><?= __('') ?></th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $l_user): ?>
                        <tr class="lease-order-row" wire:click="$dispatch('ProfileSelected', { userId: {{$l_user->id}} })">
                            <td>
                                <div class="media-left"><img src="{{ asset('assets/images/faces/face1.png') }}" class="img-circle" alt=""></div>

                                <div class="media-body">
                                    <span class="media-heading text-size-extralarge text-dark"><?=$l_user->profile?->fullname_th?> ({{$l_user->profile?->nickname}})
                                    
                                    
                                    </span>
                                    
                                    <span class=" text-size-extralarge text-dark"><?=$l_user->email?></span>
                                </div>

                                <div class="media-right media-middle">
                                    <span class="status-mark bg-{{$l_user->status?->color}}" placeholder="sss"></span>
                                </div>
                            </td>
<!--                            <td>
                                <div class="media-right media-middle">
                                    <span class="status-mark bg-grey-lighter <?= $l_user->status?->color?>"><?= $l_user->status?->name?></span>
                                </div>
                            </td>-->
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
    
        {{--{{$users}}--}}
        </div>

    

@push('scripts')
<script>
    
    
    
$.extend( $.fn.dataTable.defaults, {
    autoWidth: true,
    columnDefs: [{ 
//			orderable: false,
//			targets: [ 25 ]
    }],
    colReorder: true,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    lengthMenu: [ 10, 25, 50 ],
    language: {
            search: '_INPUT_',
            lengthMenu: '_MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    }
});

        

// Save state after reorder
$('.datatable-reorder-state-saving').DataTable({
        stateSave: true,
        fixedColumns: true,
//                scrollX: true,
//        scrollY: '500px',
//        scrollCollapse: true
        
        scrollResize: true,
        scrollX: true,
        scrollY: '100vh',
        scrollCollapse: true
        

});

// Add placeholder to the datatable filter option
$('.dataTables_filter input[type=search]').attr('placeholder','<?=__('Find')?>');

// Enable Select2 select for the length option
$('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
});


$(".lease-order-row").on("click",function () {
	$(".lease-order-row").removeClass('active');
	$(this).addClass(' active');
});
    
    
</script>
@endpush
