<!-- resources/views/livewire/user-profile.blade.php -->
<!-----------------------------  Start Product Detail    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active">
                                <a href="#tab-detail" data-toggle="tab" class="panel-title" aria-expanded="true">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Detail</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-warehouse" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Warehouse</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-user" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">User </h3>
                                    </div>
                                </a>
                            </li>

                            <li class="">
                                <a href="#tab-payment" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Payment </h3>
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-detail">

                        <div class="row col-md-12 col-xs-12">

                            <!--<div class="col-md-8 col-xs-12">-->
                                <!--<div class="panel panel-flat">-->
                                <div class="panel-heading no-padding-bottom">
                                    <h4 class="panel-title"><?= __('Branch details') ?></h4>
                                    <div class="elements">
                                        <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                        <button class="btn bg-amber-darkest"
                                            wire:click="$dispatch('showEditProfileForm')">Edit Branch</button>
                                        <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">Delete Branch</button>
                                    </div>
                                    <a class="elements-toggle"><i class="icon-more"></i></a>
                                </div>
                                <div class="list-group list-group-lg list-group-borderless">
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-3 col-xs-3 text-bold">
                                                ชื่อสาขา :
                                            </div>
                                            <div class="col-md-8 col-xs-8 text-left">
                                                {{ 'branch_name_th' }} ({{'branch_name_en'}})
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-3 col-xs-3 text-bold">
                                                เลขที่สาขา :
                                            </div>
                                            <div class="col-md-8 col-xs-8 text-left">
                                                {{ 'branch_no'}} (สาขาหลัก)
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-3 col-xs-3 text-bold">
                                                ที่อยู่ :
                                            </div>
                                            <div class="col-md-4 col-xs-4 text-left">
                                                158/9 หมู่ 1 ถ. ท่าตะโก - นครสวรรค์ ต.นครสวรรค์ออก อ.เมือง รหัสไปรษณีย์ 60000
                                            </div>
                                            <div class="col-md-4 col-xs-4 text-left">
                                                address_en 85/255, Village No. 5,
                                                            Na Kluea Sub-district,
                                                            Bang Lamung District, 
                                                            Chonburi Province, 
                                                            Postal Code 20150
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-3 col-xs-3 text-bold">
                                                ที่อยู่ออกใบเสร็จ :
                                            </div>
                                            <div class="col-md-4 col-xs-4 text-left">
                                                เหมือนที่อยู่สาขา
                                            </div>
                                            <div class="col-md-4 col-xs-4 text-left">
                                                bill_address_en 85/255, Village No. 5,
                                                            Na Kluea Sub-district,
                                                            Bang Lamung District, 
                                                            Chonburi Province, 
                                                            Postal Code 20150
                                            </div>
                                        </span>
                                    </div>


                                    <div class='row'>
                                        <div class="col-md-4 col-xs-4 text-bold">

                                            <span href="#" class="list-group-item p-l-20">
                                                <i class="icon-phone2"></i>093-443-9949
                                            </span>
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-bold">

                                            <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-mobile"></i>093-443-9949
                                        </span>
                                        </div>



                                    </div>
                                    <div class='row'>
                                        <div class="col-md-4 col-xs-4 text-bold">

                                            <span href="#" class="list-group-item p-l-20">
                                                <i class="icon-printer"></i>02-443-7482
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-bold">
                                            <span href="#" class="list-group-item p-l-20">
                                                <i class="icon-envelop3"></i>maxpower2@gmaol.co
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-bold">

                                            <span href="#" class="list-group-item p-l-20">
                                                <i class="icon-earth"></i>www.aaaa.com
                                            </span>
                                        </div>

                                    </div>
                                </div>

                            <!--</div>-->

                        </div>




                    </div>





                    <div class="tab-pane" id="tab-warehouse">


                        <div class="row col-md-12 col-xs-12">

                            <div class="table-responsive">
                                <table class="table datatable-warehouse table-striped">
                                        <thead>
                                                <tr>
                                                        <th>#</th>
                                                        <th>Warehouse Name</th>
                                                        <th>Average Remain Price</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="text-default">
                                                    <td   class="col-md-1">1.</td>
                                                    <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                    <td  class="col-md-3">756,000 บาท</td>
                                                    <td>Active</td>
                                                    <td  class="col-md-2">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>

                                            <tr class="">
                                                <td   class="col-md-1">2.</td>
                                                <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                <td  class="col-md-3">756,000 บาท</td>
                                                <td>Active</td>
                                                <td  class="col-md-2">
                                                    <ul class="icons-list">
                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                    </ul>
                                                </td>

                                            </tr>
                                            <tr class="text-default" >
                                                    <td   class="col-md-1">3.</td>
                                                    <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                    <td  class="col-md-3">756,000 บาท</td>
                                                    <td>Active</td>
                                                    <td  class="col-md-2">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>
                                        </tbody>
                                </table>
                            </div>




                        </div>



                    </div>

                    <div class="tab-pane" id="tab-user">

                        <div class="row col-md-12 col-xs-12">

<!--                            <div class="panel-heading">
                                <h4 class="panel-title">Users</h4>						
                            </div>-->
<!--                            <div class="panel-body no-padding-bottom">
                                <h3 class="panel-title">Users</h3>
                            </div>-->

                            <div class="table-responsive">
                                <table class="table datatable-stock-card table-striped">
                                        <thead>
                                                <tr>
                                                        <th>#</th>
                                                        <th>Avatar</th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="text-default" >
                                                    <td   class="col-md-1">1.</td>
                                                    <td  class="col-md-1">
                                                        <div class="thumb media-middle">
                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                    <td  class="col-md-3">somkid01</td>
                                                    <td>Active</td>
                                                    <td  class="col-md-2">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>

                                            <tr class="">
                                                        <td   class="col-md-1">2.</td>
                                                        <td  class="col-md-1">
                                                            <div class="thumb media-middle">
                                                                <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                        <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                        <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td  class="col-md-5">วิโรจน์ แมวเรื้อน (ไอขี้ตา)</td>
                                                        <td  class="col-md-3">somkid02</td>
                                                        <td>Active</td>
                                                        <td  class="col-md-2">
                                                            <ul class="icons-list">
                                                                <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                            </ul>
                                                        </td>

                                                </tr>
                                            <tr class="text-default" >
                                                    <td   class="col-md-1">3.</td>
                                                    <td  class="col-md-1">
                                                        <div class="thumb media-middle">
                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                    <td  class="col-md-3">somkid01</td>
                                                    <td>Active</td>
                                                    <td  class="col-md-2">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>
                                            <tr class="">
                                                    <td   class="col-md-1">4.</td>
                                                    <td  class="col-md-1">
                                                        <div class="thumb media-middle">
                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                    <td  class="col-md-3">somkid01</td>
                                                    <td>Deactive</td>
                                                    <td  class="col-md-2">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal"><i class="icon-eye2"></i></a></li>
                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>
                                            <tr class="text-default" >
                                                    <td   class="col-md-1">5.</td>
                                                    <td  class="col-md-1">
                                                        <div class="thumb media-middle">
                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                    <td  class="col-md-3">somkid01</td>
                                                    <td>user.status.name</td>
                                                    <td  class="col-md-3">
                                                        <ul class="icons-list">
                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                        </ul>
                                                    </td>

                                            </tr>

                                        </tbody>
                                </table>
                            </div>




                        </div>

                    </div> 



                    <div class="tab-pane" id="tab-payment">


                        <div class="row col-md-12 col-xs-12">


                            <div class="panel-heading no-padding-bottom">
                                <h4 class="panel-title"><?= __('บัญชีธนาคาร') ?></h4>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            ราคาซื้อ :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.buy_price' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ 'product.buy_vat.name' }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.buy_vat.percent' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ 'product.buy_withholding.name' }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.buy_withholding.percent' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-12 col-xs-12 text-bold">
                                            รายละเอียดการซื้อ :
                                        </div>
                                        <div class="col-md-12 col-xs-12 text-left">
                                            {{ 'product.buy_description' }}
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <div class="row col-md-12 col-xs-12">
                            <div class="panel-heading no-padding-bottom">
                                <h4 class="panel-title"><?= __('เงินสด') ?></h4>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            ราคาขาย :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.sale_price' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ 'product.sale_vat.name' }}
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.sale_vat.percent' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ 'product.sale_vat.name' }}
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ 'product.sale_withholding.percent' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-12 col-xs-12 text-bold">
                                            รายละเอียดการขาย :
                                        </div>
                                        <div class="col-md-12 col-xs-12 text-left">
                                            {{ 'product.sale_description' }}
                                        </div>
                                    </span>
                                </div>
                            </div>

                        </div>



                    </div>



                </div>
            </div>
        </div>
    </div>
</div>                



<!------------------------------------  End Product Detail ------------------------->


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
                    Livewire.dispatch('deleteUser', {
                        userId: userId
                    });
                }
            });
        }

        // Password change modal functions
        let currentUserId = null;

        function openPasswordModal(userId) {
            currentUserId = userId;
            // Update the username field in the modal
            const usernameField = document.querySelector('#passwordChangeModal #username');
            if (usernameField) {
                // Try to find the username from the link that was clicked
                const usernameText = document.querySelector(`a[data-user-id="${userId}"]`).previousElementSibling
                    .textContent.trim();
                if (usernameText) {
                    usernameField.value = usernameText;
                }
            }
            $('#passwordChangeModal').modal('show');
        }

        function submitPasswordChange() {
            // Clear previous errors
            $('#new_password_error').text('');
            $('#new_password_confirmation_error').text('');

            // Get form values
            const newPassword = $('#new_password').val();
            const newPasswordConfirmation = $('#new_password_confirmation').val();

            // Validate on client side
            let hasErrors = false;

            if (!newPassword) {
                $('#new_password_error').text('Password is required');
                hasErrors = true;
            } else if (newPassword.length < 6) {
                $('#new_password_error').text('Password must be at least 6 characters');
                hasErrors = true;
            }

            if (!newPasswordConfirmation) {
                $('#new_password_confirmation_error').text('Please confirm your password');
                hasErrors = true;
            } else if (newPassword !== newPasswordConfirmation) {
                $('#new_password_confirmation_error').text('Passwords do not match');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            // Use the stored user ID
            if (!currentUserId) {
                alert('User ID not found. Please refresh the page and try again.');
                return;
            }

            console.log('Changing password for user ID:', currentUserId);

            // Send AJAX request to change password
            $.ajax({
                url: '/users/' + currentUserId + '/change-password',
                type: 'POST',
                data: {
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirmation,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Password changed successfully!',
                    });
                    // Close modal
                    $('#passwordChangeModal').modal('hide');
                    // Clear form
                    $('#passwordChangeForm')[0].reset();
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        // Display validation errors
                        if (response.errors.new_password) {
                            $('#new_password_error').text(response.errors.new_password[0]);
                        }
                        if (response.errors.new_password_confirmation) {
                            $('#new_password_confirmation_error').text(response.errors
                                .new_password_confirmation[0]);
                        }
                    } else {
                        // Display general error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to change password. Please try again.',
                        });
                    }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

    <script>
        Livewire.on('userCreated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
            });
        });
    </script>


    <script>
        

        function initTypeahead() {
            console.log('initTypeahead');

            if (typeof $.fn.typeahead !== 'function') {
                console.warn('Typeahead plugin not found!');
                return;
            }

            const list = @json($productGroups->pluck('name'));
            console.log(list);

            $('.typeahead')
            // remove any old instance/data
            // .each(function() {
            //     const $input = $("#product_group_name");
            //     $input.data('typeahead', null);
            // })
            // re-init

            $("#product_group_name").typeahead({
                    source: list,
                    minLength: 1,
                    autoSelect: true,
                    items: list.length,
                    
                    afterSelect(item) {
                        console.log('Selected:', item);
                    }
                });

                $("#product_group_name").on('focus keyup', function(e) {
                    if ($("#product_group_name").val().length === 0) {
                        // direct lookup on the underlying instance
                        $("#product_group_name").data('typeahead').lookup();
                    }
                });
            
        }

        document.addEventListener('livewire:initialized', () => {
            console.log('livewire:initialized');
            @this.on('productSelected', () => {
                console.log('productSelected');
                setTimeout(() => {
                    initSlim();
                    initTypeahead();
                    $('.venobox').venobox();
                    document.getElementById('updateUserProfileForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });

            @this.on('addProduct', () => {
                console.log('addProduct');
                setTimeout(() => {
                    initSlim();
                    initTypeahead();
                    document.getElementById('addProductForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });
        });
    </script>
@endpush
