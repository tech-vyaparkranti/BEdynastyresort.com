<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $table = "services";

    protected $fillable = [
        'title','description' ,'category','status','image','slug'
    ];

    protected static function booted()
    {
        static::creating(function($service){
            $service->slug =  Str::slug($service->title);
        });

        static::updating(function ($service)
        {
            $service->slug = Str::slug($service->title);
        });

    }

    const ID = "id";
}
