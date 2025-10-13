<?php

namespace App\Services;

/**
 * SweetAlert Service
 * 
 * Provides standardized SweetAlert payloads for consistent UI alerts across the application.
 * All methods return arrays that can be passed to Livewire dispatch() calls or used
 * with the global JavaScript utilities.
 */
class SweetAlertService
{
    /**
     * Default configuration for SweetAlert modals
     */
    private static array $defaults = [
        'width' => '400px',
        'allowOutsideClick' => true,
        'allowEscapeKey' => true,
        'confirmButtonColor' => '#007bff',
        'cancelButtonColor' => '#6c757d',
        'confirmButtonText' => 'OK',
        'cancelButtonText' => 'Cancel',
    ];

    /**
     * Show a success alert with auto-hide timer
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function success(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false,
            'allowOutsideClick' => false,
        ], $options);

        return $payload;
    }

    /**
     * Show an error alert with confirm button
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function error(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'error',
            'showConfirmButton' => true,
            'confirmButtonText' => __t('alert.try_again', 'Try Again'),
            'confirmButtonColor' => '#dc3545',
        ], $options);

        return $payload;
    }

    /**
     * Show a confirmation dialog
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function confirm(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => __t('alert.confirm_action', 'Yes, Continue'),
            'cancelButtonText' => __t('alert.cancel_action', 'Cancel'),
            'confirmButtonColor' => '#dc3545',
            'width' => '420px',
            'allowOutsideClick' => false,
        ], $options);

        return $payload;
    }

    /**
     * Show an informational alert
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function info(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'info',
            'showConfirmButton' => true,
            'confirmButtonText' => __t('alert.ok', 'OK'),
            'confirmButtonColor' => '#17a2b8',
        ], $options);

        return $payload;
    }

    /**
     * Show a warning alert
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function warning(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'warning',
            'showConfirmButton' => true,
            'confirmButtonText' => __t('alert.ok', 'OK'),
            'confirmButtonColor' => '#ffc107',
        ], $options);

        return $payload;
    }

    /**
     * Show a question dialog (for confirmations)
     * 
     * @param string $title Alert title
     * @param string|null $message Alert message
     * @param array $options Additional options to override defaults
     * @return array SweetAlert payload
     */
    public static function question(string $title, ?string $message = null, array $options = []): array
    {
        $payload = array_merge(self::$defaults, [
            'title' => $title,
            'text' => $message,
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => __t('alert.yes', 'Yes'),
            'cancelButtonText' => __t('alert.no', 'No'),
            'confirmButtonColor' => '#28a745',
            'width' => '420px',
            'allowOutsideClick' => false,
        ], $options);

        return $payload;
    }

    /**
     * Create a custom alert with full control over options
     * 
     * @param array $options Complete SweetAlert configuration
     * @return array SweetAlert payload
     */
    public static function custom(array $options): array
    {
        return array_merge(self::$defaults, $options);
    }

    /**
     * Get default configuration values
     * 
     * @return array Default configuration
     */
    public static function getDefaults(): array
    {
        return self::$defaults;
    }

    /**
     * Set default configuration values
     * 
     * @param array $defaults New default configuration
     * @return void
     */
    public static function setDefaults(array $defaults): void
    {
        self::$defaults = array_merge(self::$defaults, $defaults);
    }
}
