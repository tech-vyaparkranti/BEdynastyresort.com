<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WebSiteElementRequest extends FormRequest
{
    use ResponseAPI;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id"=>"bail|required_if:action,update,enable,disable|nullable|exists:website_elements,id",
            "action"=>"bail|required|in:insert,update,enable,disable",
            "element"=>"bail|required_if:action,insert,update",
            "element_type"=>"bail|required_if:action,insert,update|in:Text,Image",
            "element_details_text"=>"bail|required_if:element_type,Text",
            "element_details_image"=>"bail|file|image|max:2048|required_if:element_type,Image"
        ];
    }
    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),200));
    }
}
