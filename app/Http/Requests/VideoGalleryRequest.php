<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoGalleryRequest extends FormRequest
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
            'video_link' => "bail|required_if:action,update,insert|url",
            'title' => "bail|required_if:action,update,insert|string",
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:video_galleries,id",
            "action"=>"bail|required|in:insert,update,enable,disable",
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),200));
    }
}
