<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SweetAlertStandardizationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $baseUrl = 'http://kaphongerp.test';
    protected $username = 'admin1';
    protected $password = 'password';

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TranslationSeeder']);
    }

    /**
     * Login to the application
     */
    protected function login(Browser $browser)
    {
        $browser->visit('/login')
                ->type('email', $this->username)
                ->type('password', $this->password)
                ->press('Login')
                ->waitForLocation('/dashboard', 10);
    }

    /**
     * Test SweetAlert global utilities are loaded
     */
    public function test_sweetalert_utilities_loaded()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            $browser->script('
                // Test if global utilities are available
                if (typeof window.showSweetAlert === "undefined") {
                    throw new Error("showSweetAlert utility not loaded");
                }
                if (typeof window.showSweetAlertConfirm === "undefined") {
                    throw new Error("showSweetAlertConfirm utility not loaded");
                }
                if (typeof window.resolveBoolean === "undefined") {
                    throw new Error("resolveBoolean utility not loaded");
                }
                if (typeof window.showSuccessAlert === "undefined") {
                    throw new Error("showSuccessAlert utility not loaded");
                }
                if (typeof window.showErrorAlert === "undefined") {
                    throw new Error("showErrorAlert utility not loaded");
                }
                if (typeof window.showConfirmDialog === "undefined") {
                    throw new Error("showConfirmDialog utility not loaded");
                }
            ');
            
            $this->assertTrue(true, 'All SweetAlert utilities are loaded');
        });
    }

    /**
     * Test Product Module - Sub-Unit Management
     */
    public function test_product_subunit_management()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to products
            $browser->visit('/menu/product')
                    ->waitForText('Product List', 10);
            
            // Click on first product to open detail
            $browser->click('@product-row:first-child')
                    ->waitForText('Product Detail', 10);
            
            // Test Sub-Unit Creation
            $browser->click('@add-subunit-btn')
                    ->waitFor('@subunit-modal', 5)
                    ->type('@subunit-name', 'Test Sub-Unit')
                    ->type('@subunit-sale-price', '100')
                    ->type('@subunit-buy-price', '80')
                    ->press('@save-subunit-btn')
                    ->waitForText('Sub-Unit Created!', 5)
                    ->assertSee('Sub-Unit Created!')
                    ->assertSee('New sub-unit has been created successfully')
                    ->assertSee('Sale Price')
                    ->assertSee('Buy Price');
            
            // Test Sub-Unit Deletion
            $browser->click('@delete-subunit-btn:first-child')
                    ->waitForText('Delete Sub-Unit', 5)
                    ->assertSee('Are you sure you want to delete the sub-unit')
                    ->assertSee('Yes, Continue')
                    ->assertSee('Cancel')
                    ->press('Yes, Continue')
                    ->waitForText('Sub-Unit Deleted!', 5)
                    ->assertSee('Sub-Unit Deleted!')
                    ->assertSee('Sub-unit has been deleted successfully');
        });
    }

    /**
     * Test Warehouse Module - Stock Adjustments
     */
    public function test_warehouse_stock_adjustments()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to warehouse
            $browser->visit('/menu/warehouse')
                    ->waitForText('Warehouse List', 10);
            
            // Click on first warehouse
            $browser->click('@warehouse-row:first-child')
                    ->waitForText('Warehouse Detail', 10);
            
            // Test Stock In Operation
            $browser->click('@stock-in-btn:first-child')
                    ->waitFor('@stock-modal', 5)
                    ->type('@quantity-input', '10')
                    ->press('@confirm-stock-btn')
                    ->waitForText('Confirm Stock Operation', 5)
                    ->assertSee('Confirm Stock Operation')
                    ->assertSee('Current:')
                    ->assertSee('New:')
                    ->assertSee('Yes, Continue')
                    ->assertSee('Cancel')
                    ->press('Yes, Continue')
                    ->waitForText('Success', 5)
                    ->assertSee('Stock updated successfully');
        });
    }

    /**
     * Test Warehouse Module - Transfer Operations
     */
    public function test_warehouse_transfer_operations()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to transfer page
            $browser->visit('/menu/transfer')
                    ->waitForText('Transfer Product', 10);
            
            // Test Transfer Creation
            $browser->click('@add-transfer-btn')
                    ->waitFor('@transfer-form', 5)
                    ->select('@origin-warehouse', '1')
                    ->select('@destination-warehouse', '2')
                    ->click('@add-product-btn')
                    ->waitFor('@product-selection-modal', 5)
                    ->click('@product-checkbox:first-child')
                    ->type('@transfer-quantity', '5')
                    ->press('@add-selected-products')
                    ->press('@create-transfer-btn')
                    ->waitForText('Confirm Transfer Creation', 5)
                    ->assertSee('Confirm Transfer Creation')
                    ->assertSee('Transfer Details')
                    ->assertSee('From:')
                    ->assertSee('To:')
                    ->assertSee('Products:')
                    ->assertSee('Total Cost:')
                    ->assertSee('Create Transfer')
                    ->assertSee('Cancel')
                    ->press('Create Transfer')
                    ->waitForText('Success', 5)
                    ->assertSee('Transfer slip created successfully!');
        });
    }

    /**
     * Test User Management - Password Change
     */
    public function test_user_password_change()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to user profile
            $browser->visit('/menu/user')
                    ->waitForText('User List', 10);
            
            // Click on first user
            $browser->click('@user-row:first-child')
                    ->waitForText('User Profile', 10);
            
            // Test Password Change
            $browser->click('@change-password-btn')
                    ->waitFor('@password-modal', 5)
                    ->type('@current-password', 'password')
                    ->type('@new-password', 'newpassword123')
                    ->type('@confirm-password', 'newpassword123')
                    ->press('@save-password-btn')
                    ->waitForText('Success!', 5)
                    ->assertSee('Password changed successfully!');
        });
    }

    /**
     * Test Branch Management - Delete Operation
     */
    public function test_branch_delete_operation()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to branch management
            $browser->visit('/menu/branch')
                    ->waitForText('Branch List', 10);
            
            // Test Branch Deletion
            $browser->click('@delete-branch-btn:first-child')
                    ->waitForText('Are you sure?', 5)
                    ->assertSee('Are you sure?')
                    ->assertSee('This action cannot be undone!')
                    ->assertSee('Yes, Continue')
                    ->assertSee('Cancel')
                    ->press('Yes, Continue')
                    ->waitForText('Delete Success', 5)
                    ->assertSee('Delete Success');
        });
    }

    /**
     * Test Error Handling
     */
    public function test_error_handling()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to products
            $browser->visit('/menu/product')
                    ->waitForText('Product List', 10);
            
            // Test Sub-Unit Creation with Invalid Data
            $browser->click('@product-row:first-child')
                    ->waitForText('Product Detail', 10)
                    ->click('@add-subunit-btn')
                    ->waitFor('@subunit-modal', 5)
                    ->type('@subunit-name', '') // Empty name
                    ->press('@save-subunit-btn')
                    ->waitForText('Error', 5)
                    ->assertSee('Error')
                    ->assertSee('Try Again')
                    ->assertSee('Failed to save sub-unit');
        });
    }

    /**
     * Test Translation Switching
     */
    public function test_translation_switching()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Switch to Thai
            $browser->click('@language-switcher')
                    ->click('@thai-option')
                    ->waitForText('ไทย', 5);
            
            // Test alert in Thai
            $browser->visit('/menu/product')
                    ->waitForText('รายการสินค้า', 10)
                    ->click('@product-row:first-child')
                    ->waitForText('รายละเอียดสินค้า', 10)
                    ->click('@add-subunit-btn')
                    ->waitFor('@subunit-modal', 5)
                    ->type('@subunit-name', 'ทดสอบ')
                    ->type('@subunit-sale-price', '100')
                    ->type('@subunit-buy-price', '80')
                    ->press('@save-subunit-btn')
                    ->waitForText('สำเร็จ', 5)
                    ->assertSee('สำเร็จ');
            
            // Switch back to English
            $browser->click('@language-switcher')
                    ->click('@english-option')
                    ->waitForText('English', 5);
        });
    }

    /**
     * Test Fallback Functionality (JavaScript Disabled)
     */
    public function test_fallback_functionality()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Simulate SweetAlert not being available
            $browser->script('
                // Temporarily remove SweetAlert
                window.Swal = undefined;
            ');
            
            // Test that fallback works
            $browser->visit('/menu/product')
                    ->waitForText('Product List', 10)
                    ->click('@product-row:first-child')
                    ->waitForText('Product Detail', 10)
                    ->click('@add-subunit-btn')
                    ->waitFor('@subunit-modal', 5)
                    ->type('@subunit-name', 'Test Fallback')
                    ->type('@subunit-sale-price', '100')
                    ->type('@subunit-buy-price', '80')
                    ->press('@save-subunit-btn')
                    ->waitForText('Sub-Unit Created!', 5)
                    ->assertSee('Sub-Unit Created!');
        });
    }

    /**
     * Test Multiple Alerts in Sequence
     */
    public function test_multiple_alerts_sequence()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Navigate to products
            $browser->visit('/menu/product')
                    ->waitForText('Product List', 10);
            
            // Create multiple sub-units quickly
            $browser->click('@product-row:first-child')
                    ->waitForText('Product Detail', 10);
            
            for ($i = 1; $i <= 3; $i++) {
                $browser->click('@add-subunit-btn')
                        ->waitFor('@subunit-modal', 5)
                        ->type('@subunit-name', "Test Sub-Unit {$i}")
                        ->type('@subunit-sale-price', '100')
                        ->type('@subunit-buy-price', '80')
                        ->press('@save-subunit-btn')
                        ->waitForText('Sub-Unit Created!', 5)
                        ->assertSee('Sub-Unit Created!');
            }
        });
    }

    /**
     * Test Alert Styling and Consistency
     */
    public function test_alert_styling_consistency()
    {
        $this->browse(function (Browser $browser) {
            $this->login($browser);
            
            // Test success alert styling
            $browser->visit('/menu/product')
                    ->waitForText('Product List', 10)
                    ->click('@product-row:first-child')
                    ->waitForText('Product Detail', 10)
                    ->click('@add-subunit-btn')
                    ->waitFor('@subunit-modal', 5)
                    ->type('@subunit-name', 'Style Test')
                    ->type('@subunit-sale-price', '100')
                    ->type('@subunit-buy-price', '80')
                    ->press('@save-subunit-btn')
                    ->waitForText('Sub-Unit Created!', 5);
            
            // Check alert styling
            $browser->script('
                const alert = document.querySelector(".swal2-popup");
                if (alert) {
                    const computedStyle = window.getComputedStyle(alert);
                    const width = computedStyle.width;
                    if (width !== "400px") {
                        throw new Error("Alert width should be 400px, got: " + width);
                    }
                }
            ');
            
            $this->assertTrue(true, 'Alert styling is consistent');
        });
    }
}
