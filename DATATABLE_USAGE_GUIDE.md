# DataTable Manager - Usage Guide

## Overview
The DataTable Manager is a flexible, extensible system for managing DataTables across your application. It provides a consistent API for initializing tables with external search, date filtering, and other features.

## Basic Usage

### 1. Simple Table with External Search

```html
<!-- In your Blade template -->
<div class="panel-heading">
    <div class="input-group">
        <input type="text" class="form-control" id="mySearchInput" placeholder="Search...">
        <button type="button" class="btn btn-default" onclick="clearMySearch()">Clear</button>
    </div>
</div>

<table class="table datatable-my-table table-striped">
    <!-- Your table content -->
</table>

<x-datatable-scripts listUpdatedEvent="myListUpdated" />
```

```javascript
// In your JavaScript
// Initialize table
window.DataTableManager.initTable('.datatable-my-table', {
    language: { search: '' } // Hide default search
});

// Setup external search
window.DataTableManager.setupExternalSearch('datatable-my-table', 'mySearchInput', 'clearMySearch');

// Clear function
function clearMySearch() {
    window.DataTableManager.clearFilters('datatable-my-table', 'mySearchInput');
}
```

### 2. Table with Date Range Filtering

```html
<!-- In your Blade template -->
<div class="panel-heading">
    <div class="row">
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" id="dateFrom" placeholder="From Date">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" id="dateTo" placeholder="To Date">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-default" onclick="clearAllFilters()">Clear</button>
        </div>
    </div>
</div>

<table class="table datatable-orders table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>  <!-- Date column (index 1) -->
            <th>Order #</th>
            <th>Customer</th>
            <th>Amount</th>
        </tr>
    </thead>
    <!-- Your table content -->
</table>
```

```javascript
// Initialize table
window.DataTableManager.initTable('.datatable-orders', {
    language: { search: '' }
});

// Setup search
window.DataTableManager.setupExternalSearch('datatable-orders', 'searchInput');

// Setup date filtering (date column is index 1)
window.DataTableManager.setupDateRangeFilter('datatable-orders', 'dateFrom', 'dateTo', 1);

// Clear all filters
function clearAllFilters() {
    window.DataTableManager.clearFilters('datatable-orders', 'searchInput', ['dateFrom', 'dateTo']);
}
```

### 3. Advanced Configuration

```javascript
// Custom configuration
window.DataTableManager.initTable('.datatable-advanced', {
    language: { search: '' },
    pageLength: 25,
    order: [[1, 'desc']], // Sort by second column descending
    columnDefs: [
        { orderable: false, targets: [0, 4] }, // Disable sorting on first and last columns
        { className: 'text-center', targets: [0, 4] }
    ],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    lengthMenu: [10, 25, 50, 100]
});
```

## API Reference

### DataTableManager.initTable(selector, config)
Initialize a new DataTable with custom configuration.

**Parameters:**
- `selector` (string): CSS selector for the table (e.g., '.datatable-orders')
- `config` (object): Custom configuration options

**Returns:** DataTable instance

### DataTableManager.setupExternalSearch(tableId, searchInputId, clearButtonId)
Setup external search input for a table.

**Parameters:**
- `tableId` (string): Table ID (without the dot, e.g., 'datatable-orders')
- `searchInputId` (string): ID of the search input element
- `clearButtonId` (string, optional): ID of the clear button

### DataTableManager.setupDateRangeFilter(tableId, fromDateId, toDateId, dateColumnIndex)
Setup date range filtering for a table.

**Parameters:**
- `tableId` (string): Table ID
- `fromDateId` (string): ID of the "from date" input
- `toDateId` (string): ID of the "to date" input
- `dateColumnIndex` (number): Index of the date column (0-based)

### DataTableManager.clearFilters(tableId, searchInputId, dateInputs)
Clear all filters for a table.

**Parameters:**
- `tableId` (string): Table ID
- `searchInputId` (string, optional): ID of search input to clear
- `dateInputs` (array, optional): Array of date input IDs to clear

### DataTableManager.getTable(tableId)
Get a DataTable instance.

**Parameters:**
- `tableId` (string): Table ID

**Returns:** DataTable instance or null

## Common Patterns

### 1. Product List with Search and Category Filter

```html
<div class="panel-heading">
    <div class="row">
        <div class="col-md-4">
            <input type="text" class="form-control" id="productSearch" placeholder="Search products...">
        </div>
        <div class="col-md-3">
            <select class="form-control" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default" onclick="clearProductFilters()">Clear</button>
        </div>
    </div>
</div>
```

```javascript
// Initialize
window.DataTableManager.initTable('.datatable-products', {
    language: { search: '' }
});

// Setup search
window.DataTableManager.setupExternalSearch('datatable-products', 'productSearch');

// Custom category filter
$('#categoryFilter').on('change', function() {
    const table = window.DataTableManager.getTable('datatable-products');
    const category = this.value;
    
    $.fn.dataTable.ext.search.pop(); // Remove previous filter
    
    if (category) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            return data[2] === category; // Category column is index 2
        });
    }
    
    table.draw();
});

function clearProductFilters() {
    window.DataTableManager.clearFilters('datatable-products', 'productSearch');
    $('#categoryFilter').val('');
    window.DataTableManager.getTable('datatable-products').draw();
}
```

### 2. Multi-Table Page

```javascript
// Initialize multiple tables on the same page
document.addEventListener('DOMContentLoaded', function() {
    // Table 1: Users
    window.DataTableManager.initTable('.datatable-users', {
        language: { search: '' }
    });
    window.DataTableManager.setupExternalSearch('datatable-users', 'userSearch', 'clearUserSearch');
    
    // Table 2: Orders with date filtering
    window.DataTableManager.initTable('.datatable-orders', {
        language: { search: '' }
    });
    window.DataTableManager.setupExternalSearch('datatable-orders', 'orderSearch');
    window.DataTableManager.setupDateRangeFilter('datatable-orders', 'orderDateFrom', 'orderDateTo', 1);
    
    // Table 3: Products with advanced config
    window.DataTableManager.initTable('.datatable-products', {
        language: { search: '' },
        pageLength: 20,
        order: [[0, 'asc']]
    });
    window.DataTableManager.setupExternalSearch('datatable-products', 'productSearch');
});
```

## Best Practices

1. **Consistent Naming**: Use consistent naming for table classes and IDs
   - Table class: `.datatable-{feature}`
   - Table ID: `datatable-{feature}`
   - Search input: `{feature}SearchInput`

2. **Event Namespacing**: Use namespaced events to avoid conflicts
   - Search: `keyup.search`
   - Clear: `click.clear`
   - Date filter: `change.dateFilter`

3. **Memory Management**: Tables are automatically destroyed and recreated on updates

4. **Error Handling**: Always check if table exists before using it

```javascript
const table = window.DataTableManager.getTable('my-table');
if (table) {
    table.search('query').draw();
}
```

## Migration from Old System

If you have existing DataTable code, you can easily migrate:

**Old way:**
```javascript
let myTable = $('.datatable-my-table').DataTable({
    // config
});
$('#searchInput').on('keyup', function() {
    myTable.search(this.value).draw();
});
```

**New way:**
```javascript
window.DataTableManager.initTable('.datatable-my-table', {
    // config
});
window.DataTableManager.setupExternalSearch('datatable-my-table', 'searchInput');
```

This system is designed to be:
- **Extensible**: Easy to add new features
- **Consistent**: Same API across all tables
- **Maintainable**: Centralized configuration
- **Performant**: Efficient event handling and memory management
