# Stock Adjustment Feature

## Overview
This feature allows users to adjust stock quantities for products in different warehouses directly from the product detail page.

## Features
- **Adjust Stock Button**: Added to each warehouse card in the product detail page
- **Three Operation Types**:
  - **Stock In**: Add inventory to a warehouse
  - **Stock Out**: Remove inventory from a warehouse
  - **Stock Adjustment**: Set a specific quantity for a warehouse
- **Modal Interface**: Clean popup interface for entering operation details
- **Validation**: Prevents invalid operations (e.g., stock out more than available)
- **Real-time Updates**: Page refreshes automatically after successful operations

## How to Use

### 1. Access the Feature
1. Navigate to the product detail page
2. Look for the "Adjust Stock" button in each warehouse card
3. Click the button to open the stock adjustment modal

### 2. Select Operation Type
Choose from three operation types:
- **Stock In**: Use this to add inventory to the warehouse
- **Stock Out**: Use this to remove inventory from the warehouse
- **Stock Adjustment**: Use this to set the exact quantity in the warehouse

### 3. Enter Details
- **Quantity**: Enter the quantity for the operation
- **Unit Price**: Enter the unit price (optional)
- **Sale Price**: Enter the sale price (optional)
- **Detail/Reason**: Enter a reason for the operation (optional)

### 4. Process Operation
Click "Process [Operation Type]" to execute the operation.

## Special Cases

### Total All Warehouses
When clicking "Adjust Stock" on the "Total All Warehouses" card:
- The operation will be applied to ALL warehouses that have stock for this product
- Useful for bulk operations across multiple warehouses

### Stock Out Validation
- The system prevents stock out operations that would result in negative inventory
- Shows available stock and prevents over-drawing

## Technical Implementation

### Files Modified
1. `resources/views/livewire/product/product-detail_detail-tab.blade.php`
   - Added "Adjust Stock" buttons to warehouse cards
   - Added modal interface for stock operations

2. `app/Livewire/Product/ProductDetail.php`
   - Added stock operation methods
   - Integrated with InventoryService
   - Added validation and error handling

3. `resources/views/livewire/product/product-detail.blade.php`
   - Added JavaScript for modal handling
   - Added success/error message handling

### Dependencies
- Uses `App\Services\InventoryService` for all stock operations
- Integrates with existing warehouse and product models
- Uses SweetAlert2 for user notifications

## Error Handling
- Validates all input fields
- Prevents insufficient stock operations
- Shows clear error messages
- Logs all operations for audit purposes

## Success Messages
- Shows success confirmation after operations
- Displays updated stock information
- Refreshes the page data automatically
