<!-- resources/views/livewire/user-profile.blade.php -->
<!-----------------------------  Start Product Detail    -------------------------->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="flex items-center justify-center w-full"
        style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
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
        @if($showAddEditProductForm)
            @include('livewire.product.product-add-product')
        @elseif($product)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row p-l-10 p-r-10 panel panel-flat">
                            <div class="panel-heading">
                                <!-- Back Button -->
                                @if(request()->has('return_to') && request()->get('return_to') === 'warehouse')
                                    <div class="elements">
                                        <button class="btn btn-default" onclick="goBackToWarehouse()">
                                            <i class="icon-arrow-left8"></i> Back to Warehouse Inventory
                                        </button>
                                    </div>
                                    <a class="elements-toggle"><i class="icon-more"></i></a>
                                @endif
                                
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
                                            <a href="#tab-stock-card" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Stock Card</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-trading" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Trading Detail </h3>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                @include('livewire.product.product-detail_detail-tab')
                                <div class="tab-pane" id="tab-stock-card">
                                    @if($product)
                                        @livewire('product.product-stock-card', ['productId' => $product->id], key('stock-card-' . $product->id))
                                    @else
                                        <div class="panel panel-flat">
                                            <div class="panel-body text-center">
                                                <div class="alert alert-info">
                                                    <i class="icon-info22"></i> Please select a product to view stock card details.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @include('livewire.product.product-detail_trading-tab')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-500">Select a product to view details</p>
            </div>
        @endif
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
        function initSlim() {
            // Check if DataTable is already initialized and destroy it if exists
            // This prevents duplicate initialization errors
            console.log('slim script reload');
            // Remove old script if exists
            const oldScript = document.getElementById('slim-script');
            if (oldScript) {
                oldScript.remove();
            }

            // Create and append new script using Alpine
            const script = document.createElement('script');
            script.id = 'slim-script';
            script.src = "{{ asset('slim/js/slim.kickstart.min.js') }}";
            document.body.appendChild(script);
        }

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

        function setAvatarFromSlim() {
            const slim = document.getElementById('slim-image');
            if (slim) {
                const resultImage = document.querySelector('#slim-image .slim-result img.in');
                const base64Data = resultImage.src;
                @this.set('product_cover_img', base64Data);
            }
        }

        function handleSlimSubmitForm(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('handleSlimSubmitForm');
            setAvatarFromSlim();
        }

        initSlim();

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

            // Stock modal events
            @this.on('showStockModal', () => {
                console.log('🚀 [JS] showStockModal event received');
                // Add a small delay to ensure Livewire state is updated
                setTimeout(() => {
                    $('#stockAdjustmentModal').modal('show');
                }, 100);
            });

            @this.on('hideStockModal', () => {
                console.log('🚀 [JS] hideStockModal event received');
                $('#stockAdjustmentModal').modal('hide');
                // Remove any remaining modal backdrop
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            // Handle modal close events - DISABLED FOR TESTING
            // $('#stockAdjustmentModal').on('hidden.bs.modal', function () {
            //     @this.call('closeStockModal');
            // });

            @this.on('confirmStockOperation', (data) => {
                console.log('🚀 [JS] confirmStockOperation event received:', data);

                // Temporarily hide the modal so the confirmation dialog is visible
                $('#stockAdjustmentModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');

                if (typeof Swal === 'undefined') {
                    console.warn('⚠️ [JS] SweetAlert not available, using fallback confirmation.');
                    const confirmed = window.confirm(`Confirm stock operation?\nCurrent: ${data.currentStock}\nNew: ${data.newStock}`);
                    if (confirmed) {
                        @this.call('processStockOperation', true);
                    } else {
                        setTimeout(() => {
                            $('#stockAdjustmentModal').modal('show');
                        }, 200);
                    }
                    return;
                }

                const operationLabel = data.operationType ? data.operationType.replace('_', ' ') : 'stock operation';

                Swal.fire({
                    title: 'Confirm Stock Operation',
                    html: `
                        <div style="text-align: left;">
                            <!-- Product Information -->
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                                <div style="margin-right: 15px;">
                                    <img src="${data.productImage || '{{ asset('assets/images/default_product.png') }}'}" 
                                         alt="Product Image" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                                <div>
                                    <h6 style="margin: 0 0 5px 0; color: #007bff; font-weight: bold;">${data.productName || 'N/A'}</h6>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>SKU:</strong> ${data.productSku || 'N/A'}</p>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>Warehouse:</strong> ${data.warehouseName || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <!-- Operation Details -->
                            <div style="margin-bottom: 15px;">
                                <h6 style="color: #495057; margin-bottom: 10px; font-weight: bold;">
                                    <i class="icon-cog" style="margin-right: 5px;"></i>Operation Details
                                </h6>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <div>
                                        <p style="margin: 5px 0;"><strong>Operation:</strong> <span style="color: #dc3545; font-weight: bold;">${operationLabel.toUpperCase()}</span></p>
                                        <p style="margin: 5px 0;"><strong>Current Stock:</strong> <span style="color: #007bff; font-weight: bold;">${data.currentStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                        <p style="margin: 5px 0;"><strong>New Stock:</strong> <span style="color: #28a745; font-weight: bold;">${data.newStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                    </div>
                                    <div>
                                        <p style="margin: 5px 0;"><strong>Document No:</strong> <span style="color: #6c757d;">${data.documentNumber || 'N/A'}</span></p>
                                        <p style="margin: 5px 0;"><strong>Date:</strong> <span style="color: #6c757d;">${data.operationDate || 'N/A'}</span></p>
                                        <p style="margin: 5px 0;"><strong>Time:</strong> <span style="color: #6c757d;">${data.operationTime || 'N/A'}</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quantity Change Summary -->
                            <div style="background-color: #e8f4fd; padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 15px;">
                                <p style="margin: 0; font-weight: bold; color: #495057;">
                                    Quantity Change: 
                                    <span style="color: ${(data.newStock - data.currentStock) >= 0 ? '#28a745' : '#dc3545'};">
                                        ${(data.newStock - data.currentStock) >= 0 ? '+' : ''}${(data.newStock - data.currentStock)} ${data.unitName || 'pcs'}
                                    </span>
                                </p>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Operation',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    width: '600px',
                    didOpen: () => {
                        console.log('🚀 [JS] SweetAlert opened', Swal.getPopup());
                    }
                }).then((result) => {
                    console.log('🚀 [JS] Confirmation result:', result);
                    if (result.isConfirmed) {
                        console.log('🚀 [JS] User confirmed, calling processStockOperation with confirm=true');
                        @this.call('processStockOperation', true);
                    } else {
                        console.log('🚀 [JS] User cancelled, showing modal again');
                        setTimeout(() => {
                            $('#stockAdjustmentModal').modal('show');
                        }, 200);
                    }
                });
            });

            // Success/Error message events
            @this.on('showSuccessMessage', (message) => {
                // Small delay to ensure modal is fully closed
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: message.message,
                        confirmButtonText: 'OK'
                    });
                }, 300);
            });

            @this.on('showErrorMessage', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        // Back navigation function
        function goBackToWarehouse() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnTo = urlParams.get('return_to');
            const warehouseId = urlParams.get('warehouse_id');
            
            if (returnTo === 'warehouse' && warehouseId) {
                // Navigate back to warehouse page with the specific warehouse selected and inventory tab active
                window.location.href = '{{ route("menu.menu_warehouse") }}?warehouse_id=' + warehouseId + '#tab-inventory';
            } else {
                // Fallback to browser back
                window.history.back();
            }
        }
    </script>
@endpush
