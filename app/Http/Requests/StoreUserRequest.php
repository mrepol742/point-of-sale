<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:125',
            'last_name' => 'required|string|max:125',
            'prefix' => 'required|string|max:10',
            'suffix' => 'required|string|max:10',
            'password' => 'required|string|min:6',
            'email' => 'email|max:255|unique:users,email',
            'gender' => 'nullable|string|in:male,female,other',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,cashier,production',
            'status' => 'required|string|in:active,inactive,suspended',
        ];
    }
}
