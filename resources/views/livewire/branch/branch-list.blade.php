 <!------------- Start Product List ---->

 <div class="table-responsive">
     <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
         <thead>
             <tr>
                 <th scope="col"><?= __('Products') ?></th>
             </tr>
         </thead>
         <tbody>
             @foreach ($items as $branch)
                 <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                     wire:click="$dispatch('BranchSelected', { branchId: {{ $branch->id }} })">
                     <td>
                         <div class="row col-md-12">
                             <div class="col-md-11 col-sm-11">
                                 <div class="media-body">
                                     <div class="media-heading text-size-extralarge text-dark">
                                         {{ $branch->name_th }}
                                     </div>
                                     <div class=" text-size-large text-dark">
                                         {{ $branch->branch_code }}
                                     </div>
                                     <div class=" text-size-large text-dark">
                                         {{ $branch->address_th }}, {{ $branch->phone_number }}
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-1 col-sm-1">
                                 <div class="media-right media-middle">
                                     <span class="status-mark bg-{{ $branch->is_active ? 'success' : 'danger' }}" placeholder=""></span>
                                 </div>
                             </div>
                         </div>
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
 </div>

<x-datatable-scripts listUpdatedEvent="branchListUpdated" />
