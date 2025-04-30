<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoomRequest extends FormRequest
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
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:rooms,id",
            'title'=>"bail|required_if:action,update,insert|nullable|string|max:500|unique:rooms,title",
            'features'=>"bail|nullable|string",
            'amenities'=>"bail|nullable|string",
            'banner_image'=>"bail|required_if:action,insert|nullable|image|max:2048",
            'details'=>"bail|required_if:action,update,insert|nullable",
            "action"=>"bail|required|in:insert,update,enable,disable",
            'images' => 'bail|array|required_if:action,insert|nullable',
            'images.*' => 'image',  
            'size'=>"bail|required_if:action,update,insert|nullable|numeric",
            'person_allow'=>"bail|required_if:action,update,insert|nullable|numeric",
            'category'=>"bail|required_if:action,update,insert|nullable",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),200));
    }
}
