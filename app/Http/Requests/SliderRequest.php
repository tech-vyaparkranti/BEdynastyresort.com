<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SliderRequest extends FormRequest
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
            "id"=>"bail|required_if:action,update,enable,disable|nullable|exists:slider,id",
            "action"=>"bail|required|in:insert,update,enable,disable",
            "heading_top"=>"bail|nullable|string|max:500",
            "heading_middle"=>"bail|nullable|string|max:500",
            "heading_bottom"=>"bail|nullable|string|max:500",
            "image"=>"bail|file|image|max:2048|required_if:action,insert|dimensions:ratio=16/9",
            "slide_status"=>"required_if:action,update|in:live,disabled",
            "slide_sorting"=>"required_if:action,update,insert|numeric|gt:0"
        ];
    }

    public function messages()
    {
        return [
            "heading_top.string"=>"Top Heading Text should be a valid string.",
            "heading_top.max"=>"Top Heading Text should be ,ax 500 charaters long.",
            "heading_middle.string"=>"Top Heading Text should be a valid string.",
            "heading_middle.max"=>"Top Heading Text should be ,ax 500 charaters long.",
            "heading_bottom.string"=>"Top Heading Text should be a valid string.",
            "heading_bottom.max"=>"Top Heading Text should be ,ax 500 charaters long.",
            "slide_status.required_if"=>"Slide status is requried.",
            "slide_status.in"=>"Slide status can be live or disabled.",
            "image.max"=>"Max image size should be 2 mb.",
            // "image.dimensions"=>"Dimensions should be in aspect ratio 16:9 or pixels w*h 1920*1080"
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
