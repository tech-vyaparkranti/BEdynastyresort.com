<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    use HasFactory;
    protected $table = "weddings";

    protected $fillable = [
        'title' ,'description' ,'images' ,'status' ,'short_desc',
    ];

    const ID = "id";
}
