<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Room extends Model
{
    use HasFactory;

    protected $table = "rooms";

    protected $fillable = [
        'title','images','banner_image','size','person_allow','video_link','amenities','features'
         ,'details' ,'category','status'
    ];

    const ID = "id";
    const  TITLE = "title";
    const ROOM_DETAILS = "details";
    const BANNER_IMAGE = "banner_image";
    const ROOM_IMAGE = "images";
    const SIZE = "size";
    const PERSON_ALLOW = "person_allow";
    // const PRICE = "price";
    const STATUS = "status";
    const FEATURES = "features";
    const AMENITIES = "amenities";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const VIDEO_LINK = "video_link";
    const CATEGORY = "category";

    protected static function booted()
    {
        static::creating(function ($room) {
            $room->slug = Str::slug($room->title);
        });

        static::updating(function ($room) {
            $room->slug = Str::slug($room->title);
        });
    }
}
