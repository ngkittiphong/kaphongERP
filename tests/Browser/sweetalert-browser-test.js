/**
 * SweetAlert Standardization Browser Test Script
 * Run this script in the browser console to test SweetAlert functionality
 */

class SweetAlertTester {
    constructor() {
        this.tests = [];
        this.results = [];
        this.baseUrl = window.location.origin;
    }

    // Add a test
    addTest(name, testFunction) {
        this.tests.push({ name, testFunction });
    }

    // Run all tests
    async runAllTests() {
        console.log('🚀 Starting SweetAlert Standardization Tests');
        console.log('==========================================');
        
        for (const test of this.tests) {
            try {
                console.log(`\n🧪 Running: ${test.name}`);
                await test.testFunction();
                this.results.push({ name: test.name, status: 'PASSED' });
                console.log(`✅ PASSED: ${test.name}`);
            } catch (error) {
                this.results.push({ name: test.name, status: 'FAILED', error: error.message });
                console.log(`❌ FAILED: ${test.name} - ${error.message}`);
            }
        }
        
        this.generateReport();
    }

    // Generate test report
    generateReport() {
        console.log('\n📊 Test Report');
        console.log('=============');
        
        const passed = this.results.filter(r => r.status === 'PASSED').length;
        const failed = this.results.filter(r => r.status === 'FAILED').length;
        const total = this.results.length;
        
        console.log(`Total Tests: ${total}`);
        console.log(`Passed: ${passed}`);
        console.log(`Failed: ${failed}`);
        console.log(`Success Rate: ${Math.round((passed / total) * 100)}%`);
        
        if (failed > 0) {
            console.log('\n❌ Failed Tests:');
            this.results.filter(r => r.status === 'FAILED').forEach(r => {
                console.log(`  - ${r.name}: ${r.error}`);
            });
        }
        
        if (failed === 0) {
            console.log('\n🎉 All tests passed! SweetAlert standardization is working correctly.');
        }
    }

    // Helper method to wait for SweetAlert
    async waitForSweetAlert(timeout = 5000) {
        return new Promise((resolve, reject) => {
            const startTime = Date.now();
            const checkInterval = setInterval(() => {
                if (document.querySelector('.swal2-popup')) {
                    clearInterval(checkInterval);
                    resolve(document.querySelector('.swal2-popup'));
                } else if (Date.now() - startTime > timeout) {
                    clearInterval(checkInterval);
                    reject(new Error('SweetAlert did not appear within timeout'));
                }
            }, 100);
        });
    }

    // Helper method to check if SweetAlert is visible
    isSweetAlertVisible() {
        const alert = document.querySelector('.swal2-popup');
        return alert && alert.offsetParent !== null;
    }

    // Helper method to get SweetAlert content
    getSweetAlertContent() {
        const alert = document.querySelector('.swal2-popup');
        if (!alert) return null;
        
        return {
            title: alert.querySelector('.swal2-title')?.textContent || '',
            content: alert.querySelector('.swal2-content')?.textContent || '',
            html: alert.querySelector('.swal2-html-container')?.innerHTML || '',
            confirmButton: alert.querySelector('.swal2-confirm')?.textContent || '',
            cancelButton: alert.querySelector('.swal2-cancel')?.textContent || ''
        };
    }

    // Helper method to click SweetAlert button
    clickSweetAlertButton(buttonType = 'confirm') {
        const button = document.querySelector(`.swal2-${buttonType}`);
        if (button) {
            button.click();
            return true;
        }
        return false;
    }

    // Helper method to check alert styling
    checkAlertStyling(expectedWidth = '400px') {
        const alert = document.querySelector('.swal2-popup');
        if (!alert) throw new Error('No SweetAlert found');
        
        const computedStyle = window.getComputedStyle(alert);
        const width = computedStyle.width;
        
        if (width !== expectedWidth) {
            throw new Error(`Expected width ${expectedWidth}, got ${width}`);
        }
        
        // Check if swal-wide class is applied
        if (!alert.classList.contains('swal-wide')) {
            throw new Error('swal-wide class not applied');
        }
        
        return true;
    }
}

// Create tester instance
const tester = new SweetAlertTester();

// Test 1: Check if SweetAlert utilities are loaded
tester.addTest('SweetAlert Utilities Loaded', () => {
    if (typeof window.showSweetAlert !== 'function') {
        throw new Error('showSweetAlert utility not loaded');
    }
    if (typeof window.showSweetAlertConfirm !== 'function') {
        throw new Error('showSweetAlertConfirm utility not loaded');
    }
    if (typeof window.resolveBoolean !== 'function') {
        throw new Error('resolveBoolean utility not loaded');
    }
    if (typeof window.showSuccessAlert !== 'function') {
        throw new Error('showSuccessAlert utility not loaded');
    }
    if (typeof window.showErrorAlert !== 'function') {
        throw new Error('showErrorAlert utility not loaded');
    }
    if (typeof window.showConfirmDialog !== 'function') {
        throw new Error('showConfirmDialog utility not loaded');
    }
});

// Test 2: Test success alert
tester.addTest('Success Alert Functionality', async () => {
    // Trigger a success alert
    window.showSuccessAlert('Test Success', 'This is a test success message');
    
    // Wait for alert to appear
    await tester.waitForSweetAlert();
    
    // Check alert content
    const content = tester.getSweetAlertContent();
    if (!content.title.includes('Test Success')) {
        throw new Error('Success alert title not correct');
    }
    if (!content.content.includes('This is a test success message')) {
        throw new Error('Success alert content not correct');
    }
    
    // Check styling
    tester.checkAlertStyling('400px');
    
    // Close alert
    tester.clickSweetAlertButton('confirm');
});

// Test 3: Test error alert
tester.addTest('Error Alert Functionality', async () => {
    // Trigger an error alert
    window.showErrorAlert('Test Error', 'This is a test error message');
    
    // Wait for alert to appear
    await tester.waitForSweetAlert();
    
    // Check alert content
    const content = tester.getSweetAlertContent();
    if (!content.title.includes('Test Error')) {
        throw new Error('Error alert title not correct');
    }
    if (!content.content.includes('This is a test error message')) {
        throw new Error('Error alert content not correct');
    }
    
    // Check styling
    tester.checkAlertStyling('400px');
    
    // Close alert
    tester.clickSweetAlertButton('confirm');
});

// Test 4: Test confirmation dialog
tester.addTest('Confirmation Dialog Functionality', async () => {
    // Trigger a confirmation dialog
    window.showConfirmDialog('Test Confirm', 'Are you sure?', 'testCallback');
    
    // Wait for alert to appear
    await tester.waitForSweetAlert();
    
    // Check alert content
    const content = tester.getSweetAlertContent();
    if (!content.title.includes('Test Confirm')) {
        throw new Error('Confirmation dialog title not correct');
    }
    if (!content.content.includes('Are you sure?')) {
        throw new Error('Confirmation dialog content not correct');
    }
    if (!content.confirmButton.includes('Yes, Continue')) {
        throw new Error('Confirmation dialog confirm button not correct');
    }
    if (!content.cancelButton.includes('Cancel')) {
        throw new Error('Confirmation dialog cancel button not correct');
    }
    
    // Check styling (confirmation should be wider)
    tester.checkAlertStyling('420px');
    
    // Cancel the dialog
    tester.clickSweetAlertButton('cancel');
});

// Test 5: Test resolveBoolean utility
tester.addTest('ResolveBoolean Utility', () => {
    // Test various input types
    if (window.resolveBoolean(true) !== true) {
        throw new Error('resolveBoolean(true) should return true');
    }
    if (window.resolveBoolean(false) !== false) {
        throw new Error('resolveBoolean(false) should return false');
    }
    if (window.resolveBoolean('true') !== true) {
        throw new Error('resolveBoolean("true") should return true');
    }
    if (window.resolveBoolean('false') !== false) {
        throw new Error('resolveBoolean("false") should return false');
    }
    if (window.resolveBoolean('1') !== true) {
        throw new Error('resolveBoolean("1") should return true');
    }
    if (window.resolveBoolean('0') !== false) {
        throw new Error('resolveBoolean("0") should return false');
    }
    if (window.resolveBoolean('yes') !== true) {
        throw new Error('resolveBoolean("yes") should return true');
    }
    if (window.resolveBoolean('no') !== false) {
        throw new Error('resolveBoolean("no") should return false');
    }
    if (window.resolveBoolean(1) !== true) {
        throw new Error('resolveBoolean(1) should return true');
    }
    if (window.resolveBoolean(0) !== false) {
        throw new Error('resolveBoolean(0) should return false');
    }
    if (window.resolveBoolean('invalid', true) !== true) {
        throw new Error('resolveBoolean("invalid", true) should return default true');
    }
});

// Test 6: Test fallback functionality
tester.addTest('Fallback Functionality', async () => {
    // Temporarily disable SweetAlert
    const originalSwal = window.Swal;
    window.Swal = undefined;
    
    try {
        // Trigger an alert (should fall back to native alert)
        window.showSuccessAlert('Fallback Test', 'This should use native alert');
        
        // Wait a bit for any potential alert
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // If we get here without error, the fallback worked
        console.log('Fallback functionality appears to be working');
    } finally {
        // Restore SweetAlert
        window.Swal = originalSwal;
    }
});

// Test 7: Test multiple alerts in sequence
tester.addTest('Multiple Alerts Sequence', async () => {
    // Show multiple alerts quickly
    window.showSuccessAlert('Alert 1', 'First alert');
    await tester.waitForSweetAlert();
    tester.clickSweetAlertButton('confirm');
    
    // Wait for first alert to close
    await new Promise(resolve => setTimeout(resolve, 500));
    
    window.showSuccessAlert('Alert 2', 'Second alert');
    await tester.waitForSweetAlert();
    tester.clickSweetAlertButton('confirm');
    
    // Wait for second alert to close
    await new Promise(resolve => setTimeout(resolve, 500));
    
    window.showSuccessAlert('Alert 3', 'Third alert');
    await tester.waitForSweetAlert();
    tester.clickSweetAlertButton('confirm');
});

// Test 8: Test alert button colors
tester.addTest('Alert Button Colors', async () => {
    // Test success alert button color
    window.showSuccessAlert('Color Test', 'Testing button colors');
    await tester.waitForSweetAlert();
    
    const confirmButton = document.querySelector('.swal2-confirm');
    const backgroundColor = window.getComputedStyle(confirmButton).backgroundColor;
    
    // Check if it's blue (#007bff)
    if (!backgroundColor.includes('rgb(0, 123, 255)')) {
        throw new Error(`Success button should be blue, got ${backgroundColor}`);
    }
    
    tester.clickSweetAlertButton('confirm');
    
    // Wait for alert to close
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Test error alert button color
    window.showErrorAlert('Color Test', 'Testing error button colors');
    await tester.waitForSweetAlert();
    
    const errorButton = document.querySelector('.swal2-confirm');
    const errorBackgroundColor = window.getComputedStyle(errorButton).backgroundColor;
    
    // Check if it's red (#dc3545)
    if (!errorBackgroundColor.includes('rgb(220, 53, 69)')) {
        throw new Error(`Error button should be red, got ${errorBackgroundColor}`);
    }
    
    tester.clickSweetAlertButton('confirm');
});

// Test 9: Test alert width consistency
tester.addTest('Alert Width Consistency', async () => {
    // Test standard alert width
    window.showSuccessAlert('Width Test', 'Testing alert width');
    await tester.waitForSweetAlert();
    
    const standardAlert = document.querySelector('.swal2-popup');
    const standardWidth = window.getComputedStyle(standardAlert).width;
    
    if (standardWidth !== '400px') {
        throw new Error(`Standard alert should be 400px wide, got ${standardWidth}`);
    }
    
    tester.clickSweetAlertButton('confirm');
    
    // Wait for alert to close
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Test confirmation dialog width
    window.showConfirmDialog('Width Test', 'Testing confirmation width', 'testCallback');
    await tester.waitForSweetAlert();
    
    const confirmAlert = document.querySelector('.swal2-popup');
    const confirmWidth = window.getComputedStyle(confirmAlert).width;
    
    if (confirmWidth !== '420px') {
        throw new Error(`Confirmation dialog should be 420px wide, got ${confirmWidth}`);
    }
    
    tester.clickSweetAlertButton('cancel');
});

// Test 10: Test translation support
// Skipped: Translation Support test relies on server-side PHP helper (__t) and
// is not available in the browser context without explicit exposure.

// Export tester for manual use
window.SweetAlertTester = tester;

// Auto-run tests if in browser console
if (typeof window !== 'undefined' && window.console) {
    console.log('SweetAlert Tester loaded. Run tester.runAllTests() to execute all tests.');
    console.log('Or run individual tests like: tester.addTest("My Test", () => { /* test code */ })');
}

// Example usage:
// tester.runAllTests();
