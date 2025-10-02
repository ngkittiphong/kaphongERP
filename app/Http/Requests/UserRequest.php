<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\ValidationRulesService;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return ValidationRulesService::getUserCreationRules();
    }

    public function messages()
    {
        return ValidationRulesService::getUserCreationMessages();
    }
}
