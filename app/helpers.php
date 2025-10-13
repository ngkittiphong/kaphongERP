<?php

if (!function_exists('__t')) {
    /**
     * Get translation for a key and locale
     *
     * @param string $key Translation key
     * @param string|null $default Default value if translation not found
     * @param string|null $locale Locale code (defaults to current locale)
     * @return string
     */
    function __t(string $key, ?string $default = null, ?string $locale = null): string
    {
        // If no locale specified, check session and set app locale
        if ($locale === null && request()->hasSession()) {
            $sessionLocale = request()->session()->get('locale', 'en');
            $supportedLocales = ['en', 'th'];
            if (in_array($sessionLocale, $supportedLocales)) {
                app()->setLocale($sessionLocale);
            }
        }
        
        return app(\App\Services\TranslationService::class)->get($key, $locale, $default);
    }
}

if (!function_exists('currency')) {
    /**
     * Format a number as currency using the global currency configuration
     *
     * @param float|int|null $amount The amount to format
     * @param string|null $currencyCode Optional currency code (defaults to config)
     * @param int|null $decimalPlaces Optional decimal places (defaults to config)
     * @return string
     */
    function currency($amount = null, ?string $currencyCode = null, ?int $decimalPlaces = null): string
    {
        if ($amount === null) {
            return config('currency.symbol', '฿');
        }
        
        $currencyCode = $currencyCode ?? config('currency.code', 'THB');
        $decimalPlaces = $decimalPlaces ?? config('currency.decimal_places', 2);
        $decimalSeparator = config('currency.decimal_separator', '.');
        $thousandsSeparator = config('currency.thousands_separator', ',');
        $symbolPosition = config('currency.symbol_position', 'before');
        $spaceBetweenSymbol = config('currency.space_between_symbol', false);
        
        // Get currency symbol
        $symbol = config("currency.currencies.{$currencyCode}.symbol", config('currency.symbol', '฿'));
        
        // Format the number
        $formattedAmount = number_format($amount, $decimalPlaces, $decimalSeparator, $thousandsSeparator);
        
        // Add currency symbol
        $space = $spaceBetweenSymbol ? ' ' : '';
        
        if ($symbolPosition === 'before') {
            return $symbol . $space . $formattedAmount;
        } else {
            return $formattedAmount . $space . $symbol;
        }
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get the currency symbol
     *
     * @param string|null $currencyCode Optional currency code (defaults to config)
     * @return string
     */
    function currency_symbol(?string $currencyCode = null): string
    {
        $currencyCode = $currencyCode ?? config('currency.code', 'THB');
        return config("currency.currencies.{$currencyCode}.symbol", config('currency.symbol', '฿'));
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get the currency code
     *
     * @return string
     */
    function currency_code(): string
    {
        return config('currency.code', 'THB');
    }
}

if (!function_exists('currency_name')) {
    /**
     * Get the currency name
     *
     * @param string|null $currencyCode Optional currency code (defaults to config)
     * @return string
     */
    function currency_name(?string $currencyCode = null): string
    {
        $currencyCode = $currencyCode ?? config('currency.code', 'THB');
        return config("currency.currencies.{$currencyCode}.name", config('currency.name', 'Thai Baht'));
    }
}

if (!function_exists('swal_success')) {
    /**
     * Show a success SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_success(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->success($title, $message, $options);
    }
}

if (!function_exists('swal_error')) {
    /**
     * Show an error SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_error(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->error($title, $message, $options);
    }
}

if (!function_exists('swal_confirm')) {
    /**
     * Show a confirmation SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_confirm(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->confirm($title, $message, $options);
    }
}

if (!function_exists('swal_info')) {
    /**
     * Show an info SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_info(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->info($title, $message, $options);
    }
}

if (!function_exists('swal_warning')) {
    /**
     * Show a warning SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_warning(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->warning($title, $message, $options);
    }
}

if (!function_exists('swal_question')) {
    /**
     * Show a question SweetAlert
     *
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options
     * @return array SweetAlert payload
     */
    function swal_question(string $title, ?string $message = null, array $options = []): array
    {
        return app(\App\Services\SweetAlertService::class)->question($title, $message, $options);
    }
}
