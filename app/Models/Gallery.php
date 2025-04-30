<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = "galleries";

    protected $fillable = [
        'title' ,'image','video_link','details','status','category'
    ];

    const ID = "id";
}
