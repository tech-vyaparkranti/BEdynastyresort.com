<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactUsRequest extends FormRequest
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
            "name"=>"required|string|max:50",
            // "last_name"=>"required|string|max:50",
            // "address"=>"required|string|max:50",
            // "email"=>"required|email|max:100",
            "phone_number"=>"required|integer",
            // "message"=>"required|string|max:1000",
            // "captcha"=>"required|captcha",
            // "country_code"=>"nullable|string|max:10"
        ];
    }

    public function messages()
    {
        return [
            // "captcha.captcha"=>"Invalid captcha text.",
            // "name.required"=>"Full Name is required.",
            // "name.string"=>"Full Name should be string.",
            // "name.string"=>"Full Name cannot be string greater than 100 characters."
            "name.required" => "Full Name is required.",
            "name.string" => "Full Name should be a string.",
            "name.max" => "Full Name cannot exceed 100 characters.",
            // "email.required" => "Email is required.",
            // "email.email" => "Email must be a valid email address.",
            // "email.max" => "Email cannot exceed 255 characters.",
            // "phone_number.required" => "Phone Number is required.",
            "phone_number.numeric" => "Phone Number must be numeric.",
            "phone_number.digits_between" => "Phone Number must be between 9 to 20 digits.",
            "message.max" => "Message cannot exceed 1000 characters.",
            // "services.max" => "Service type cannot exceed 100 characters.",
            // "travel_date.date" => "Travel Date must be a valid date.",
            // "travel_date.after_or_equal" => "Travel Date cannot be in the past.",
            // "traveller_count.integer" => "Traveller Count must be an integer.",
            // "traveller_count.min" => "Traveller Count must be at least 1."
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
