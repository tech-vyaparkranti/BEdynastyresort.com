<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WedVenuseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    use ResponseAPI;

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
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:wed_venuses,id",
            'title'=>"bail|required_if:action,update,insert|nullable|string|max:500",
            'icon'=>"bail|required_if:action,insert|nullable|image|max:2048",
            'description'=>"bail|required_if:action,update,insert|nullable",
            "action"=>"bail|required|in:insert,update,enable,disable",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),422));
    }
}
