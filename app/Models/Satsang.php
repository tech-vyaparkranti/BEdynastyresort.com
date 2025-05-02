<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satsang extends Model
{
    use HasFactory;

    protected $table = "satsangs";

    protected $fillable = [
        'title' ,'description' ,'images' ,'status' ,'short_desc',
    ];

    const ID = "id";
}
