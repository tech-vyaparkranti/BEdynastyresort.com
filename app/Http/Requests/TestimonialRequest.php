<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TestimonialRequest extends FormRequest
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
        $id = $this->input('id');   
        return [
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:testimonials,id",
            'name'=>"bail|required_if:action,update,insert|nullable",
            'description'=>"bail|required_if:action,update,insert|nullable",
            'designation'=>"bail|required_if:action,update,insert|nullable",
            'review'=>"bail|required_if:action,update,insert|nullable|max:5|numeric",
            'status' =>"bail|required_if:action,update,insert|nullable",
            'image' =>"bail|required_if:action,insert|nullable|image",
            'sorting' => [
                    'bail',
                    'required_if:action,update,insert',
                    'nullable',
                    'numeric',
                    Rule::unique('testimonials', 'sorting')->ignore($id),
                ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
                'data' => null,
            ], 422)
        );
    }
}
