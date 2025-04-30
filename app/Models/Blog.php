<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;
    
    protected $table = "blogs";

    const ID = "id";
    const IMAGE = "image";
    const TITLE = "title";
    const CONTENT = "content";
    const BLOG_DATE = "blog_date";
    const FACEBOOK_LINK = "facebook_link";
    const INSTAGRAM_LINK = "instagram_link";
    const YOUTUBE_LINK = "youtube_link";
    const TWITTER_LINK = "twitter_link";
    const META_KEYWORD = "meta_keyword";
    const META_TITLE = "meta_title";
    const META_DESCRIPTION = "meta_description";
    const BLOG_CATEGORY = "blog_category";
    const BLOG_STATUS = "blog_status";
    const BLOG_SORTING = "blog_sorting";
    const STATUS = "status";
    const CREATED_BY = "created_by";
    const UPDATED_BY = "updated_by";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const BLOG_IMAGES = 'blog_images';


    const BLOG_STATUS_LIVE = "live";
    const BLOG_STATUS_DISABLED = "disabled";
    #"live","disabled"

    protected static function boot() {
        parent::boot();
        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title, '-');
        });
    }
}
