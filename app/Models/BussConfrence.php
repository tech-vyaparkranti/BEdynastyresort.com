<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BussConfrence extends Model
{
    use HasFactory;
    protected $table = "buss_confrences";

    protected $fillable = [
        'title' ,'description' ,'images' ,'status' ,'short_desc',
    ];

    const ID = "id";
}
