<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
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
            'id'=>"bail|required_if:action,update,enable,disable|nullable|exists:f_a_q_s,id",
            'questions'=>"bail|required_if:action,update,insert|nullable|array",
            'answers'=>"bail|required_if:action,update,insert|nullable|array",
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $questions = $this->input('questions', []);
            $answers = $this->input('answers', []);

            if (is_array($questions) && is_array($answers)) {
                foreach ($questions as $index => $question) {
                    if (!isset($answers[$index]) || $answers[$index] === null) {
                        $validator->errors()->add("answers.$index", "An answer is required for question at index $index.");
                    }
                }
            }
        });
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
