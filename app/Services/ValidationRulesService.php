<?php

namespace App\Services;

/**
 * Centralized Validation Rules Service
 * 
 * This service provides a single source of truth for all validation rules
 * across the application. This ensures consistency and makes maintenance easier.
 * 
 * Usage Examples:
 * 
 * // In Controllers:
 * $validator = Validator::make($request->all(), 
 *     ValidationRulesService::getUserCreationRules(),
 *     ValidationRulesService::getUserCreationMessages()
 * );
 * 
 * // In Form Requests:
 * public function rules() {
 *     return ValidationRulesService::getUserCreationRules();
 * }
 * 
 * // In Livewire Components:
 * protected $rules = [];
 * public function mount() {
 *     $this->rules = ValidationRulesService::getForcePasswordChangeRules();
 * }
 * 
 * // For password length in JavaScript:
 * {{ \App\Services\ValidationRulesService::getPasswordRules()['min_length'] }}
 */
class ValidationRulesService
{
    /**
     * Get user creation validation rules
     */
    public static function getUserCreationRules(): array
    {
        return [
            // User fields
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
            'user_type_id' => 'required|exists:user_types,id',
            'user_status_id' => 'required|exists:user_statuses,id',
            
            // Profile fields
            'nickname' => 'nullable|string|max:100',
            'card_id_no' => 'nullable|string|max:100',
            'fullname_th' => 'nullable|string|max:255',
            'fullname_en' => 'nullable|string|max:255',
            'prefix_en' => 'nullable|string|max:50',
            'prefix_th' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get user update validation rules
     */
    public static function getUserUpdateRules(int $userId): array
    {
        return [
            // User fields
            'username' => 'required|string|unique:users,username,' . $userId . '|max:255',
            'email' => 'required|email|unique:users,email,' . $userId . '|max:255',
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable|same:password',
            'user_type_id' => 'required|exists:user_types,id',
            'user_status_id' => 'required|exists:user_statuses,id',
            
            // Profile fields
            'nickname' => 'nullable|string|max:100',
            'card_id_no' => 'nullable|string|max:100',
            'fullname_th' => 'nullable|string|max:255',
            'fullname_en' => 'nullable|string|max:255',
            'prefix_en' => 'nullable|string|max:50',
            'prefix_th' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get password change validation rules
     */
    public static function getPasswordChangeRules(): array
    {
        return [
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required|same:new_password',
            'request_change_pass' => 'boolean',
        ];
    }

    /**
     * Get force password change validation rules
     */
    public static function getForcePasswordChangeRules(): array
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }

    /**
     * Get user creation validation messages
     */
    public static function getUserCreationMessages(): array
    {
        return [
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken.',
            'username.max' => 'The username may not be greater than 255 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
            'user_type_id.required' => 'The user type field is required.',
            'user_type_id.exists' => 'The selected user type is invalid.',
            'user_status_id.required' => 'The user status field is required.',
            'user_status_id.exists' => 'The selected user status is invalid.',
            'nickname.max' => 'The nickname may not be greater than 100 characters.',
            'card_id_no.max' => 'The card ID number may not be greater than 100 characters.',
            'fullname_th.max' => 'The full name in Thai may not be greater than 255 characters.',
            'fullname_en.max' => 'The full name in English may not be greater than 255 characters.',
            'prefix_en.max' => 'The prefix in English may not be greater than 50 characters.',
            'prefix_th.max' => 'The prefix in Thai may not be greater than 50 characters.',
            'birth_date.date' => 'The birth date is not a valid date.',
            'description.string' => 'The description must be a string.',
        ];
    }

    /**
     * Get password change validation messages
     */
    public static function getPasswordChangeMessages(): array
    {
        return [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password_confirmation.required' => 'Password confirmation is required.',
            'new_password_confirmation.same' => 'Password confirmation does not match.',
            'request_change_pass.boolean' => 'The request change pass field must be true or false.',
        ];
    }

    /**
     * Get force password change validation messages
     */
    public static function getForcePasswordChangeMessages(): array
    {
        return [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password_confirmation.required' => 'Password confirmation is required.',
            'new_password_confirmation.same' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Get all password-related rules (for easy access)
     */
    public static function getPasswordRules(): array
    {
        return [
            'min_length' => 8,
            'rules' => 'required|min:8',
            'confirmed_rules' => 'required|min:8|confirmed',
        ];
    }

    /**
     * Get all password-related messages (for easy access)
     */
    public static function getPasswordMessages(): array
    {
        return [
            'required' => 'Password is required.',
            'min' => 'Password must be at least 8 characters.',
            'confirmed' => 'Passwords do not match.',
        ];
    }
}
