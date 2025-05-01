<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;

    protected $table = "packages";

    protected $fillable = [
        "title" ,'price','offer_price','allowance_details','short_desc' ,'features' ,'description' ,'status',"image" ,"category"
    ];

    const ID = "id";
}
