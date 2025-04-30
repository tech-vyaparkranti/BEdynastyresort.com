<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DonateRequest extends FormRequest
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
            "id" => "bail|required_if:action,update,enable,disable|nullable|exists:donate,id",
            "action" => "bail|required|in:insert,update,enable,disable",
            // "heading_top" => "bail|nullable|string",
            "heading_top" => "bail|string|max:500",
            "heading_middle" => "bail|nullable|array",
            "heading_middle.*" => "bail|string|max:500",
            "heading_bottom" => "bail|nullable|string|max:500",
            "image" => "bail|file|image|max:2048|required_if:action,insert",
            "slide_status" => "required_if:action,update|in:live,disabled",
            "slide_sorting" => "required_if:action,update,insert|numeric|gt:0"
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // "heading_top.array" => "Heading Top must be an array of strings.",
            // "heading_top.*.string" => "Each Heading Top item must be a valid string.",
            // "heading_top.*.max" => "Each Heading Top item can be up to 500 characters long.",
            // "heading_middle.array" => "Heading Middle must be an array of strings.",
            // "heading_middle.*.string" => "Each Heading Middle item must be a valid string.",
            // "heading_middle.*.max" => "Each Heading Middle item can be up to 500 characters long.",
            // "heading_bottom.string" => "Bottom Heading Text should be a valid string.",
            // "heading_bottom.max" => "Bottom Heading Text can be up to 500 characters long.",
            "slide_status.required_if" => "Slide status is required.",
            "slide_status.in" => "Slide status can be either 'live' or 'disabled'.",
            "image.max" => "The image size should not exceed 2 MB.",
            "image.dimensions" => "The image dimensions should have a 16:9 aspect ratio."
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(), 200));
    }
}
