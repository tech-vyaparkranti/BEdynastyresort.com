<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackagesRequest extends FormRequest
{
    use ResponseAPI;
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
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:packages,id",
            'title'=>"bail|required_if:action,update,insert|nullable|string|max:500",
            'features'=>"bail|nullable",
            'description'=>"bail|nullable",
            'image'=>"bail|required_if:action,insert|nullable|image|max:2048",
            'category'=>"bail|required_if:action,update,insert|nullable",
            "action"=>"bail|required|in:insert,update,enable,disable",
            'short_desc'=>"bail|required_if:action,update,insert|nullable",
            'allowance_details'=>"bail|required_if:action,update,insert|nullable|max:50",
            'price'=>"bail|required_if:action,update,insert|nullable",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),200));
    }
}
