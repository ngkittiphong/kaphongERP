#!/bin/bash

# SweetAlert Standardization Test Runner
# This script runs automated tests for the SweetAlert refactor

echo "🚀 Starting SweetAlert Standardization Tests"
echo "=========================================="

# Configuration
BASE_URL="http://kaphongerp.test"
USERNAME="admin1"
PASSWORD="password"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test counter
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# Function to run a test
run_test() {
    local test_name="$1"
    local test_command="$2"
    
    echo -e "\n${BLUE}Running: ${test_name}${NC}"
    echo "Command: ${test_command}"
    
    TOTAL_TESTS=$((TOTAL_TESTS + 1))
    
    if eval "$test_command"; then
        echo -e "${GREEN}✅ PASSED: ${test_name}${NC}"
        PASSED_TESTS=$((PASSED_TESTS + 1))
    else
        echo -e "${RED}❌ FAILED: ${test_name}${NC}"
        FAILED_TESTS=$((FAILED_TESTS + 1))
    fi
}

# Function to check if application is accessible
check_app_accessibility() {
    echo -e "\n${YELLOW}Checking application accessibility...${NC}"
    
    if curl -s -o /dev/null -w "%{http_code}" "$BASE_URL" | grep -q "200\|302"; then
        echo -e "${GREEN}✅ Application is accessible at $BASE_URL${NC}"
        return 0
    else
        echo -e "${RED}❌ Application is not accessible at $BASE_URL${NC}"
        echo "Please ensure the application is running and accessible"
        return 1
    fi
}

# Function to run Laravel Dusk tests
run_dusk_tests() {
    echo -e "\n${YELLOW}Running Laravel Dusk Tests...${NC}"
    
    if [ -f "tests/Browser/SweetAlertStandardizationTest.php" ]; then
        run_test "Laravel Dusk - SweetAlert Utilities Loaded" "php artisan dusk --filter=test_sweetalert_utilities_loaded"
        run_test "Laravel Dusk - Product Sub-Unit Management" "php artisan dusk --filter=test_product_subunit_management"
        run_test "Laravel Dusk - Warehouse Stock Adjustments" "php artisan dusk --filter=test_warehouse_stock_adjustments"
        run_test "Laravel Dusk - Warehouse Transfer Operations" "php artisan dusk --filter=test_warehouse_transfer_operations"
        run_test "Laravel Dusk - User Password Change" "php artisan dusk --filter=test_user_password_change"
        run_test "Laravel Dusk - Branch Delete Operation" "php artisan dusk --filter=test_branch_delete_operation"
        run_test "Laravel Dusk - Error Handling" "php artisan dusk --filter=test_error_handling"
        run_test "Laravel Dusk - Translation Switching" "php artisan dusk --filter=test_translation_switching"
        run_test "Laravel Dusk - Fallback Functionality" "php artisan dusk --filter=test_fallback_functionality"
        run_test "Laravel Dusk - Multiple Alerts Sequence" "php artisan dusk --filter=test_multiple_alerts_sequence"
        run_test "Laravel Dusk - Alert Styling Consistency" "php artisan dusk --filter=test_alert_styling_consistency"
    else
        echo -e "${RED}❌ Laravel Dusk test file not found${NC}"
    fi
}

# Function to run Playwright tests
run_playwright_tests() {
    echo -e "\n${YELLOW}Running Playwright Tests...${NC}"
    
    if [ -f "tests/playwright/sweetalert-tests.spec.js" ]; then
        if command -v npx &> /dev/null; then
            run_test "Playwright - All SweetAlert Tests" "npx playwright test tests/playwright/sweetalert-tests.spec.js --headed"
        else
            echo -e "${RED}❌ npx not found. Please install Node.js and npm${NC}"
        fi
    else
        echo -e "${RED}❌ Playwright test file not found${NC}"
    fi
}

# Function to run manual test scenarios
run_manual_tests() {
    echo -e "\n${YELLOW}Manual Test Scenarios (requires browser interaction)${NC}"
    echo "Please manually test the following scenarios:"
    echo ""
    echo "1. Product Module:"
    echo "   - Navigate to Products → Click product → Add Sub-Unit → Fill form → Save"
    echo "   - Expected: Success alert with auto-hide timer"
    echo "   - Delete Sub-Unit → Confirm"
    echo "   - Expected: Confirmation dialog → Success alert"
    echo ""
    echo "2. Warehouse Module:"
    echo "   - Navigate to Warehouse → Click warehouse → Stock In → Enter quantity → Confirm"
    echo "   - Expected: Confirmation dialog with stock details → Success alert"
    echo "   - Navigate to Transfer → Create transfer → Add products → Confirm"
    echo "   - Expected: Complex confirmation dialog → Success alert"
    echo ""
    echo "3. User Management:"
    echo "   - Navigate to Users → Click user → Change Password → Fill form → Save"
    echo "   - Expected: Success alert with auto-hide"
    echo ""
    echo "4. Branch Management:"
    echo "   - Navigate to Branches → Delete branch → Confirm"
    echo "   - Expected: Confirmation dialog → Success alert"
    echo ""
    echo "5. Error Handling:"
    echo "   - Try to save sub-unit with empty name"
    echo "   - Expected: Error alert with 'Try Again' button"
    echo ""
    echo "6. Translation:"
    echo "   - Switch language to Thai → Perform any operation"
    echo "   - Expected: Alerts in Thai language"
    echo ""
    echo "7. Fallback:"
    echo "   - Disable JavaScript → Perform operations"
    echo "   - Expected: Native alert/confirm dialogs"
}

# Function to generate test report
generate_report() {
    echo -e "\n${YELLOW}Test Report${NC}"
    echo "=========="
    echo "Total Tests: $TOTAL_TESTS"
    echo -e "Passed: ${GREEN}$PASSED_TESTS${NC}"
    echo -e "Failed: ${RED}$FAILED_TESTS${NC}"
    
    if [ $FAILED_TESTS -eq 0 ]; then
        echo -e "\n${GREEN}🎉 All tests passed! SweetAlert standardization is working correctly.${NC}"
    else
        echo -e "\n${RED}⚠️  Some tests failed. Please review the failures above.${NC}"
    fi
    
    # Calculate percentage
    if [ $TOTAL_TESTS -gt 0 ]; then
        PERCENTAGE=$((PASSED_TESTS * 100 / TOTAL_TESTS))
        echo "Success Rate: $PERCENTAGE%"
    fi
}

# Main execution
main() {
    echo "SweetAlert Standardization Test Runner"
    echo "Base URL: $BASE_URL"
    echo "Username: $USERNAME"
    echo "Password: [HIDDEN]"
    
    # Check if application is accessible
    if ! check_app_accessibility; then
        echo -e "${RED}❌ Cannot proceed with tests. Application is not accessible.${NC}"
        exit 1
    fi
    
    # Run different types of tests
    run_dusk_tests
    run_playwright_tests
    
    # Show manual test scenarios
    run_manual_tests
    
    # Generate final report
    generate_report
}

# Check if running in Laravel project directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ This script must be run from the Laravel project root directory${NC}"
    exit 1
fi

# Run main function
main
