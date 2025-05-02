<?php

namespace App\Http\Requests;

use App\Models\Blog;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BlogRequest extends FormRequest
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
    // public function rules()
    // {
    //     return [
    //         "id"=>"bail|required_if:action,update,enable,disable|nullable|exists:blogs,id",
    //         "action"=>"bail|required|in:insert,update,enable,disable",
    //         "title"=>"bail|required|string|max:500",
    //         "content"=>"bail|required",
    //         "blog_date"=>"bail|required",
    //         "facebook_link"=>"bail|nullable|string|max:500",
    //         "instagram_link"=>"bail|nullable|string|max:500",
    //         "youtube_link"=>"bail|nullable|string|max:500",
    //         "twitter_link"=>"bail|nullable|string|max:500",
    //         "blog_category"=>"bail|nullable|string|max:500",
    //         "image"=>"bail|file|image|max:2048|required_if:action,insert",
    //         "blog_status"=>"required_if:action,update|in:live,disabled",
    //         "blog_sorting"=>"required_if:action,update,insert|numeric|gt:0"
    //     ];
    // }

    public function rules()
    {
        $action = $this->input('action');

        switch ($action) {
            case 'enable':
            case 'disable':
                return [
                    Blog::ID => 'required|exists:blogs,' . Blog::ID,
                ];

            case 'insert':
                return [
                    Blog::TITLE => 'required|string',
                    Blog::CONTENT => 'required|string',
                    Blog::BLOG_DATE => 'required|date',
                    Blog::BLOG_CATEGORY => 'required|string',
                    Blog::META_KEYWORD => 'bail|nullable',
                    Blog::META_TITLE => 'bail|nullable',
                    Blog::META_DESCRIPTION => 'bail|nullable',
                    Blog::BLOG_SORTING => 'nullable|integer',
                    "status" => 'required',
                    Blog::IMAGE => 'nullable|image|mimes:jpeg,png,jpg',
                    'blog_images' => 'required|array',
                    'blog_images.*' => 'image',                                   
                ];

            case 'update':
                return [
                    Blog::ID => 'required|exists:blogs,' . Blog::ID,
                    Blog::TITLE => 'required|string',
                    Blog::CONTENT => 'required|string',
                    Blog::BLOG_DATE => 'required|date',
                    Blog::BLOG_CATEGORY => 'required|string',
                    Blog::META_KEYWORD => 'bail|nullable',
                    Blog::META_TITLE => 'bail|nullable',
                    Blog::META_DESCRIPTION => 'bail|nullable',
                    Blog::BLOG_SORTING => 'nullable|integer',
                    "status" => 'required',
                    Blog::IMAGE => 'nullable|image|mimes:jpeg,png,jpg',
                ];

            default:
                return [];
        }
    }
    
    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),422));
    }
}
