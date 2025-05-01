<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WedTimeline extends Model
{
    use HasFactory;

    protected $table = "wed_timelines";

    protected $fillable = [
        'title' ,'description' ,'status'
    ];

    const ID = "id";
}
