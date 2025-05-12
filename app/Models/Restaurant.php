<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = "restaurants";

    protected $fillable = [
        'title' ,'description' ,'status','images'
    ];

    const ID = "id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const IMAGE = "images";
    const STATUS = "status";
}
