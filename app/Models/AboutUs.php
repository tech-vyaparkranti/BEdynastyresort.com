<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = "about_us";

    protected $fillable = [
        'title' ,'description' ,'image' ,'status'
    ];

    const ID = "id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const IMAGE = "image";
    const STATUS = "status";
}
