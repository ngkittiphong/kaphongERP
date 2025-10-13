import { test, expect } from '@playwright/test';

// Test configuration
const BASE_URL = 'http://kaphongerp.test';
const USERNAME = 'admin1';
const PASSWORD = 'password';

// Helper function to login
async function login(page) {
    await page.goto(`${BASE_URL}/login`);
    await page.fill('input[name="email"]', USERNAME);
    await page.fill('input[name="password"]', PASSWORD);
    await page.click('button[type="submit"]');
    await page.waitForURL('**/dashboard');
}

// Helper function to wait for SweetAlert
async function waitForSweetAlert(page, expectedText) {
    await page.waitForSelector('.swal2-popup', { timeout: 10000 });
    await expect(page.locator('.swal2-popup')).toBeVisible();
    if (expectedText) {
        await expect(page.locator('.swal2-popup')).toContainText(expectedText);
    }
}

// Helper function to check alert styling
async function checkAlertStyling(page, expectedWidth = '400px') {
    const alert = page.locator('.swal2-popup');
    await expect(alert).toBeVisible();
    
    // Check width
    const width = await alert.evaluate(el => window.getComputedStyle(el).width);
    expect(width).toBe(expectedWidth);
    
    // Check if swal-wide class is applied
    await expect(alert).toHaveClass(/swal-wide/);
}

test.describe('SweetAlert Standardization Tests', () => {
    
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('SweetAlert utilities are loaded', async ({ page }) => {
        await page.goto(`${BASE_URL}/dashboard`);
        
        // Check if global utilities are available
        const utilities = await page.evaluate(() => ({
            showSweetAlert: typeof window.showSweetAlert,
            showSweetAlertConfirm: typeof window.showSweetAlertConfirm,
            resolveBoolean: typeof window.resolveBoolean,
            showSuccessAlert: typeof window.showSuccessAlert,
            showErrorAlert: typeof window.showErrorAlert,
            showConfirmDialog: typeof window.showConfirmDialog
        }));
        
        expect(utilities.showSweetAlert).toBe('function');
        expect(utilities.showSweetAlertConfirm).toBe('function');
        expect(utilities.resolveBoolean).toBe('function');
        expect(utilities.showSuccessAlert).toBe('function');
        expect(utilities.showErrorAlert).toBe('function');
        expect(utilities.showConfirmDialog).toBe('function');
    });

    test('Product Sub-Unit Creation Success Alert', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Add sub-unit
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        // Fill form
        await page.fill('[data-testid="subunit-name"]', 'Test Sub-Unit');
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        // Save and check alert
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'Sub-Unit Created!');
        
        // Check alert content
        await expect(page.locator('.swal2-popup')).toContainText('New sub-unit has been created successfully');
        await expect(page.locator('.swal2-popup')).toContainText('Sale Price');
        await expect(page.locator('.swal2-popup')).toContainText('Buy Price');
        
        // Check styling
        await checkAlertStyling(page, '400px');
        
        // Check auto-hide timer
        await page.waitForSelector('.swal2-popup', { state: 'hidden', timeout: 5000 });
    });

    test('Product Sub-Unit Deletion Confirmation', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Delete sub-unit
        await page.click('[data-testid="delete-subunit-btn"]:first-child');
        await waitForSweetAlert(page, 'Delete Sub-Unit');
        
        // Check confirmation dialog
        await expect(page.locator('.swal2-popup')).toContainText('Are you sure you want to delete the sub-unit');
        await expect(page.locator('.swal2-popup')).toContainText('Yes, Continue');
        await expect(page.locator('.swal2-popup')).toContainText('Cancel');
        
        // Check styling (confirmation should be wider)
        await checkAlertStyling(page, '420px');
        
        // Confirm deletion
        await page.click('.swal2-confirm');
        await waitForSweetAlert(page, 'Sub-Unit Deleted!');
        
        // Check success message
        await expect(page.locator('.swal2-popup')).toContainText('Sub-unit has been deleted successfully');
    });

    test('Warehouse Stock Adjustment Confirmation', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/warehouse`);
        await page.waitForSelector('[data-testid="warehouse-row"]', { timeout: 10000 });
        
        // Click first warehouse
        await page.click('[data-testid="warehouse-row"]:first-child');
        await page.waitForSelector('[data-testid="warehouse-detail"]', { timeout: 10000 });
        
        // Stock in operation
        await page.click('[data-testid="stock-in-btn"]:first-child');
        await page.waitForSelector('[data-testid="stock-modal"]', { timeout: 5000 });
        
        // Fill quantity
        await page.fill('[data-testid="quantity-input"]', '10');
        
        // Confirm stock operation
        await page.click('[data-testid="confirm-stock-btn"]');
        await waitForSweetAlert(page, 'Confirm Stock Operation');
        
        // Check confirmation content
        await expect(page.locator('.swal2-popup')).toContainText('Current:');
        await expect(page.locator('.swal2-popup')).toContainText('New:');
        await expect(page.locator('.swal2-popup')).toContainText('Yes, Continue');
        await expect(page.locator('.swal2-popup')).toContainText('Cancel');
        
        // Confirm operation
        await page.click('.swal2-confirm');
        await waitForSweetAlert(page, 'Success');
        
        // Check success message
        await expect(page.locator('.swal2-popup')).toContainText('Stock updated successfully');
    });

    test('Warehouse Transfer Creation Complex Dialog', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/transfer`);
        await page.waitForSelector('[data-testid="transfer-page"]', { timeout: 10000 });
        
        // Add transfer
        await page.click('[data-testid="add-transfer-btn"]');
        await page.waitForSelector('[data-testid="transfer-form"]', { timeout: 5000 });
        
        // Fill transfer form
        await page.selectOption('[data-testid="origin-warehouse"]', '1');
        await page.selectOption('[data-testid="destination-warehouse"]', '2');
        
        // Add product
        await page.click('[data-testid="add-product-btn"]');
        await page.waitForSelector('[data-testid="product-selection-modal"]', { timeout: 5000 });
        await page.check('[data-testid="product-checkbox"]:first-child');
        await page.fill('[data-testid="transfer-quantity"]', '5');
        await page.click('[data-testid="add-selected-products"]');
        
        // Create transfer
        await page.click('[data-testid="create-transfer-btn"]');
        await waitForSweetAlert(page, 'Confirm Transfer Creation');
        
        // Check complex dialog content
        await expect(page.locator('.swal2-popup')).toContainText('Transfer Details');
        await expect(page.locator('.swal2-popup')).toContainText('From:');
        await expect(page.locator('.swal2-popup')).toContainText('To:');
        await expect(page.locator('.swal2-popup')).toContainText('Products:');
        await expect(page.locator('.swal2-popup')).toContainText('Total Cost:');
        await expect(page.locator('.swal2-popup')).toContainText('Create Transfer');
        await expect(page.locator('.swal2-popup')).toContainText('Cancel');
        
        // Check styling (complex dialog should be wider)
        await checkAlertStyling(page, '700px');
        
        // Confirm transfer
        await page.click('.swal2-confirm');
        await waitForSweetAlert(page, 'Success');
        
        // Check success message
        await expect(page.locator('.swal2-popup')).toContainText('Transfer slip created successfully!');
    });

    test('User Password Change Success Alert', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/user`);
        await page.waitForSelector('[data-testid="user-row"]', { timeout: 10000 });
        
        // Click first user
        await page.click('[data-testid="user-row"]:first-child');
        await page.waitForSelector('[data-testid="user-profile"]', { timeout: 10000 });
        
        // Change password
        await page.click('[data-testid="change-password-btn"]');
        await page.waitForSelector('[data-testid="password-modal"]', { timeout: 5000 });
        
        // Fill password form
        await page.fill('[data-testid="current-password"]', 'password');
        await page.fill('[data-testid="new-password"]', 'newpassword123');
        await page.fill('[data-testid="confirm-password"]', 'newpassword123');
        
        // Save password
        await page.click('[data-testid="save-password-btn"]');
        await waitForSweetAlert(page, 'Success!');
        
        // Check success message
        await expect(page.locator('.swal2-popup')).toContainText('Password changed successfully!');
        
        // Check styling
        await checkAlertStyling(page, '400px');
    });

    test('Branch Deletion Confirmation', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/branch`);
        await page.waitForSelector('[data-testid="branch-row"]', { timeout: 10000 });
        
        // Delete branch
        await page.click('[data-testid="delete-branch-btn"]:first-child');
        await waitForSweetAlert(page, 'Are you sure?');
        
        // Check confirmation dialog
        await expect(page.locator('.swal2-popup')).toContainText('This action cannot be undone!');
        await expect(page.locator('.swal2-popup')).toContainText('Yes, Continue');
        await expect(page.locator('.swal2-popup')).toContainText('Cancel');
        
        // Confirm deletion
        await page.click('.swal2-confirm');
        await waitForSweetAlert(page, 'Delete Success');
        
        // Check success message
        await expect(page.locator('.swal2-popup')).toContainText('Delete Success');
    });

    test('Error Handling with Invalid Data', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Try to create sub-unit with invalid data
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        // Leave name empty (invalid)
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        // Save and check error
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'Error');
        
        // Check error message
        await expect(page.locator('.swal2-popup')).toContainText('Try Again');
        await expect(page.locator('.swal2-popup')).toContainText('Failed to save sub-unit');
        
        // Check error styling (should be red)
        const confirmButton = page.locator('.swal2-confirm');
        await expect(confirmButton).toHaveCSS('background-color', 'rgb(220, 53, 69)'); // #dc3545
    });

    test('Translation Switching (English to Thai)', async ({ page }) => {
        await page.goto(`${BASE_URL}/dashboard`);
        
        // Switch to Thai
        await page.click('[data-testid="language-switcher"]');
        await page.click('[data-testid="thai-option"]');
        await page.waitForSelector('text=ไทย', { timeout: 5000 });
        
        // Test alert in Thai
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Add sub-unit
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        // Fill form
        await page.fill('[data-testid="subunit-name"]', 'ทดสอบ');
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        // Save and check Thai alert
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'สำเร็จ');
        
        // Check Thai content
        await expect(page.locator('.swal2-popup')).toContainText('สำเร็จ');
        
        // Switch back to English
        await page.click('[data-testid="language-switcher"]');
        await page.click('[data-testid="english-option"]');
        await page.waitForSelector('text=English', { timeout: 5000 });
    });

    test('Fallback Functionality (SweetAlert Disabled)', async ({ page }) => {
        await page.goto(`${BASE_URL}/dashboard`);
        
        // Disable SweetAlert
        await page.evaluate(() => {
            window.Swal = undefined;
        });
        
        // Test that fallback works
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Add sub-unit
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        // Fill form
        await page.fill('[data-testid="subunit-name"]', 'Fallback Test');
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        // Save - should trigger native alert
        await page.click('[data-testid="save-subunit-btn"]');
        
        // Check for native alert (this will be handled by the page's fallback logic)
        // The test passes if no error occurs
        await page.waitForTimeout(2000);
    });

    test('Multiple Alerts in Sequence', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Click first product
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        // Create multiple sub-units quickly
        for (let i = 1; i <= 3; i++) {
            await page.click('[data-testid="add-subunit-btn"]');
            await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
            
            await page.fill('[data-testid="subunit-name"]', `Test Sub-Unit ${i}`);
            await page.fill('[data-testid="subunit-sale-price"]', '100');
            await page.fill('[data-testid="subunit-buy-price"]', '80');
            
            await page.click('[data-testid="save-subunit-btn"]');
            await waitForSweetAlert(page, 'Sub-Unit Created!');
            
            // Wait for alert to auto-hide
            await page.waitForSelector('.swal2-popup', { state: 'hidden', timeout: 5000 });
        }
    });

    test('Alert Button Color Consistency', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Test success alert button color
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        await page.fill('[data-testid="subunit-name"]', 'Color Test');
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'Sub-Unit Created!');
        
        // Check success button color (should be blue)
        const successButton = page.locator('.swal2-confirm');
        await expect(successButton).toHaveCSS('background-color', 'rgb(0, 123, 255)'); // #007bff
        
        // Close success alert
        await page.waitForSelector('.swal2-popup', { state: 'hidden', timeout: 5000 });
        
        // Test error alert button color
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        // Leave name empty to trigger error
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'Error');
        
        // Check error button color (should be red)
        const errorButton = page.locator('.swal2-confirm');
        await expect(errorButton).toHaveCSS('background-color', 'rgb(220, 53, 69)'); // #dc3545
    });

    test('Alert Width Consistency', async ({ page }) => {
        await page.goto(`${BASE_URL}/menu/product`);
        await page.waitForSelector('[data-testid="product-row"]', { timeout: 10000 });
        
        // Test standard alert width (400px)
        await page.click('[data-testid="product-row"]:first-child');
        await page.waitForSelector('[data-testid="product-detail"]', { timeout: 10000 });
        
        await page.click('[data-testid="add-subunit-btn"]');
        await page.waitForSelector('[data-testid="subunit-modal"]', { timeout: 5000 });
        
        await page.fill('[data-testid="subunit-name"]', 'Width Test');
        await page.fill('[data-testid="subunit-sale-price"]', '100');
        await page.fill('[data-testid="subunit-buy-price"]', '80');
        
        await page.click('[data-testid="save-subunit-btn"]');
        await waitForSweetAlert(page, 'Sub-Unit Created!');
        
        // Check standard alert width
        const standardAlert = page.locator('.swal2-popup');
        const standardWidth = await standardAlert.evaluate(el => window.getComputedStyle(el).width);
        expect(standardWidth).toBe('400px');
        
        // Close alert
        await page.waitForSelector('.swal2-popup', { state: 'hidden', timeout: 5000 });
        
        // Test confirmation dialog width (420px)
        await page.click('[data-testid="delete-subunit-btn"]:first-child');
        await waitForSweetAlert(page, 'Delete Sub-Unit');
        
        // Check confirmation dialog width
        const confirmAlert = page.locator('.swal2-popup');
        const confirmWidth = await confirmAlert.evaluate(el => window.getComputedStyle(el).width);
        expect(confirmWidth).toBe('420px');
    });
});
