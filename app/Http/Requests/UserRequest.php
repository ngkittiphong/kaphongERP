<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            // User fields
            'username'       => 'required|string|unique:users,username|max:255',
            'email'          => 'required|email|unique:users,email|max:255',
            'password'       => 'required|min:6|confirmed',
            'user_type_id'   => 'required|exists:user_types,id',
            'user_status_id' => 'required|exists:user_statuses,id',

            // Profile fields
            'nickname'       => 'nullable|string|max:100',
            'card_id_no'     => 'nullable|string|max:100',
            'fullname_th'    => 'nullable|string|max:255',
            'fullname_en'    => 'nullable|string|max:255',
            'prefix_en'      => 'nullable|string|max:50',
            'prefix_th'      => 'nullable|string|max:50',
            'birth_date'     => 'nullable|date',
            'description'    => 'nullable|string',
        ];
        
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'Passwords do not match.',
            'user_type_id.required' => 'The user type field is required.',
            'user_type_id.exists' => 'The selected user type is invalid.',
            'user_status_id.required' => 'The user status field is required.',
            'user_status_id.exists' => 'The selected user status is invalid.',
            'card_id_no.max' => 'The card ID number may not be greater than 100 characters.',
            'fullname_th.max' => 'The full name in Thai may not be greater than 255 characters.',
            'fullname_en.max' => 'The full name in English may not be greater than 255 characters.',
            'prefix_en.max' => 'The prefix in English may not be greater than 50 characters.',
            'prefix_th.max' => 'The prefix in Thai may not be greater than 50 characters.',
            'birth_date.date' => 'The birth date is not a valid date.',
            'description.string' => 'The description must be a string.',
        ];
    }
}
