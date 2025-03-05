<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'email' => 'required|email|min:6|max:30',
            'password' => 'required|min:6|max:60',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.max' => 'Name cannot be longer than 30 characters',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.min' => 'Email must be at least 6 characters',
            'email.max' => 'Email cannot be longer than 30 characters',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password cannot be longer than 60 characters',
        ];
    }
}
