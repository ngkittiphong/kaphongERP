# SweetAlert Standardization - Automated Testing Guide

## Overview

This guide provides comprehensive automated testing for the SweetAlert standardization refactor. The tests cover all major functionality including success alerts, error handling, confirmation dialogs, translations, and fallback scenarios.

## Test Files Created

### 1. Laravel Dusk Tests (`tests/Browser/SweetAlertStandardizationTest.php`)
- **Purpose**: Full browser automation tests using Laravel Dusk
- **Coverage**: Complete user workflows across all modules
- **Requirements**: Laravel Dusk installed and configured

### 2. Playwright Tests (`tests/playwright/sweetalert-tests.spec.js`)
- **Purpose**: Cross-browser testing with Playwright
- **Coverage**: Detailed UI testing and styling verification
- **Requirements**: Node.js and Playwright installed

### 3. Browser Console Tests (`tests/browser/sweetalert-browser-test.js`)
- **Purpose**: Quick testing directly in browser console
- **Coverage**: Core functionality and utility functions
- **Requirements**: None (runs in any browser)

### 4. Test Runner Script (`test-sweetalert.sh`)
- **Purpose**: Automated execution of all test suites
- **Coverage**: Orchestrates all testing approaches
- **Requirements**: Bash shell

## Quick Start Testing

### Option 1: Browser Console Testing (Fastest)

1. Open your browser and navigate to `http://kaphongerp.test`
2. Login with credentials: `admin1` / `password`
3. Open browser console (F12)
4. Copy and paste the contents of `tests/browser/sweetalert-browser-test.js`
5. Run: `tester.runAllTests()`

**Expected Output:**
```
🚀 Starting SweetAlert Standardization Tests
==========================================

🧪 Running: SweetAlert Utilities Loaded
✅ PASSED: SweetAlert Utilities Loaded

🧪 Running: Success Alert Functionality
✅ PASSED: Success Alert Functionality

... (more tests)

📊 Test Report
=============
Total Tests: 10
Passed: 10
Failed: 0
Success Rate: 100%

🎉 All tests passed! SweetAlert standardization is working correctly.
```

### Option 2: Laravel Dusk Testing (Most Comprehensive)

1. Ensure Laravel Dusk is installed:
   ```bash
   composer require laravel/dusk
   php artisan dusk:install
   ```

2. Run the SweetAlert tests:
   ```bash
   php artisan dusk tests/Browser/SweetAlertStandardizationTest.php
   ```

3. For specific test:
   ```bash
   php artisan dusk tests/Browser/SweetAlertStandardizationTest.php::test_product_subunit_management
   ```

### Option 3: Playwright Testing (Cross-Browser)

1. Install Playwright:
   ```bash
   npm install -D @playwright/test
   npx playwright install
   ```

2. Run tests:
   ```bash
   npx playwright test tests/playwright/sweetalert-tests.spec.js --headed
   ```

3. Run specific test:
   ```bash
   npx playwright test tests/playwright/sweetalert-tests.spec.js -g "Product Sub-Unit"
   ```

### Option 4: Automated Test Runner (All-in-One)

1. Make the script executable:
   ```bash
   chmod +x test-sweetalert.sh
   ```

2. Run all tests:
   ```bash
   ./test-sweetalert.sh
   ```

## Test Scenarios Covered

### ✅ Core Functionality Tests

1. **SweetAlert Utilities Loading**
   - Verifies all global utilities are available
   - Checks function signatures and availability

2. **Success Alert Functionality**
   - Tests success alert display
   - Verifies auto-hide timer (3 seconds)
   - Checks content and styling

3. **Error Alert Functionality**
   - Tests error alert display
   - Verifies "Try Again" button
   - Checks error styling (red buttons)

4. **Confirmation Dialog Functionality**
   - Tests confirmation dialog display
   - Verifies "Yes, Continue" and "Cancel" buttons
   - Checks wider width (420px)

5. **ResolveBoolean Utility**
   - Tests boolean normalization from various input types
   - Verifies string, number, and boolean handling

### ✅ Module-Specific Tests

6. **Product Module - Sub-Unit Management**
   - Create sub-unit → Success alert with pricing summary
   - Edit sub-unit → Success alert with updated info
   - Delete sub-unit → Confirmation dialog → Success alert
   - Error handling → Error alert with validation messages

7. **Warehouse Module - Stock Adjustments**
   - Stock in operation → Confirmation with current/new stock
   - Stock out operation → Confirmation dialog
   - Stock adjustment → Success alert

8. **Warehouse Module - Transfer Operations**
   - Complex transfer confirmation dialog
   - Transfer details display (from/to, products, cost)
   - Success alert after transfer creation

9. **User Management - Password Change**
   - Password change success alert
   - Error handling for invalid passwords
   - Form validation feedback

10. **Branch Management - Delete Operations**
    - Branch deletion confirmation
    - Success alert after deletion

### ✅ Advanced Functionality Tests

11. **Translation Support**
    - English to Thai language switching
    - Alert text translation verification
    - Button text translation

12. **Fallback Functionality**
    - SweetAlert disabled scenario
    - Native alert/confirm fallback
    - Graceful degradation

13. **Multiple Alerts Sequence**
    - Rapid alert display handling
    - Memory leak prevention
    - UI responsiveness

14. **Alert Styling Consistency**
    - Button color verification (success: blue, error: red)
    - Width consistency (alerts: 400px, confirms: 420px)
    - CSS class application (.swal-wide)

15. **Error Recovery**
    - Network error handling
    - Validation error display
    - Form state maintenance

## Test Data Requirements

### Database Setup
Ensure the following data exists for comprehensive testing:

```sql
-- Products with sub-units
INSERT INTO products (name, sku, unit_name) VALUES ('Test Product', 'TEST001', 'pcs');

-- Warehouses
INSERT INTO warehouses (name, status) VALUES ('Main Warehouse', 'active');
INSERT INTO warehouses (name, status) VALUES ('Secondary Warehouse', 'active');

-- Users
INSERT INTO users (name, email, password) VALUES ('Test User', 'test@example.com', 'password');

-- Branches
INSERT INTO branches (name, status) VALUES ('Test Branch', 'active');
```

### Translation Keys
Ensure alert translation keys are seeded:

```bash
php artisan db:seed --class=TranslationSeeder
```

## Expected Test Results

### ✅ Success Criteria

- **All utilities loaded**: 100% function availability
- **Alert display**: Proper content and styling
- **Button functionality**: Confirm/cancel actions work
- **Auto-hide timers**: Success alerts auto-hide after 3 seconds
- **Error handling**: Proper error display with retry options
- **Translation**: Alert text changes with language switching
- **Fallback**: Native dialogs work when SweetAlert unavailable
- **Styling**: Consistent colors and widths across all alerts

### ❌ Failure Indicators

- **Missing utilities**: Functions not available in global scope
- **Alert not appearing**: SweetAlert not triggered or displayed
- **Wrong content**: Alert text doesn't match expected values
- **Styling issues**: Wrong colors, widths, or CSS classes
- **Button failures**: Confirm/cancel buttons not working
- **Translation failures**: Alert text not translating
- **Fallback failures**: Native dialogs not working

## Troubleshooting

### Common Issues

1. **Tests fail with "SweetAlert not loaded"**
   - Check if SweetAlert2 CDN is loaded
   - Verify global utilities are in global_scripts.blade.php
   - Check browser console for JavaScript errors

2. **Translation tests fail**
   - Ensure TranslationSeeder has been run
   - Check if language switching is working
   - Verify translation keys exist in database

3. **Styling tests fail**
   - Check if CSS classes are applied correctly
   - Verify button color CSS variables
   - Check for CSS conflicts

4. **Dusk tests fail**
   - Ensure ChromeDriver is installed and updated
   - Check if application is accessible at test URL
   - Verify test data exists in database

### Debug Commands

```bash
# Check if SweetAlert is loaded
php artisan tinker
>>> app('view')->exists('includes.global_scripts')

# Check translation keys
php artisan tinker
>>> app(\App\Services\TranslationService::class)->get('alert.success_title')

# Check database
php artisan tinker
>>> \App\Models\TranslationKey::where('key', 'like', 'alert.%')->get()
```

## Performance Testing

### Load Testing
```bash
# Test multiple rapid alerts
for i in {1..10}; do
  curl -X POST http://kaphongerp.test/api/test-alert
done
```

### Memory Testing
```javascript
// In browser console
const initialMemory = performance.memory?.usedJSHeapSize || 0;
// Run multiple alerts
for (let i = 0; i < 100; i++) {
  window.showSuccessAlert(`Test ${i}`, 'Memory test');
}
const finalMemory = performance.memory?.usedJSHeapSize || 0;
console.log(`Memory usage: ${(finalMemory - initialMemory) / 1024 / 1024} MB`);
```

## Continuous Integration

### GitHub Actions Example
```yaml
name: SweetAlert Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run Dusk tests
        run: php artisan dusk tests/Browser/SweetAlertStandardizationTest.php
```

## Test Maintenance

### Adding New Tests
1. Add test method to appropriate test file
2. Update test runner script
3. Document new test scenario
4. Update this guide

### Updating Tests
1. Modify test assertions for new requirements
2. Update expected values and selectors
3. Test across different browsers
4. Verify CI/CD pipeline compatibility

## Conclusion

This comprehensive testing suite ensures the SweetAlert standardization refactor works correctly across all modules and maintains consistent user experience. The tests cover both happy path scenarios and edge cases, providing confidence in the implementation.

Run the tests regularly during development and before deployment to catch any regressions or issues early.
