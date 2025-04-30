<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
                'image' => "bail|required_if:action,insert|nullable|image|max:2048",
                'name' => "bail|required_if:action,update,insert|nullable|string|max:500",
                'designation' => "bail|required_if:action,update,insert|nullable|string",
                'position' => "bail|required_if:action,update,insert|nullable|string",
        ];
    }
}
