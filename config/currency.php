<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the currency settings for the application.
    | You can easily change the currency symbol, code, and formatting here.
    |
    */

    'symbol' => '฿',
    'code' => 'THB',
    'name' => 'Thai Baht',
    
    /*
    |--------------------------------------------------------------------------
    | Currency Formatting
    |--------------------------------------------------------------------------
    |
    | Configure how currency values are formatted
    |
    */
    
    'decimal_places' => 2,
    'decimal_separator' => '.',
    'thousands_separator' => ',',
    'symbol_position' => 'before', // 'before' or 'after'
    'space_between_symbol' => false, // true or false
    
    /*
    |--------------------------------------------------------------------------
    | Alternative Currencies
    |--------------------------------------------------------------------------
    |
    | You can define multiple currencies here for future use
    |
    */
    
    'currencies' => [
        'THB' => [
            'symbol' => '฿',
            'name' => 'Thai Baht',
            'decimal_places' => 2,
        ],
        'USD' => [
            'symbol' => '$',
            'name' => 'US Dollar',
            'decimal_places' => 2,
        ],
        'EUR' => [
            'symbol' => '€',
            'name' => 'Euro',
            'decimal_places' => 2,
        ],
    ],
];
