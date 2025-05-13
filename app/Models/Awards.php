<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Awards extends Model
{
    use HasFactory;

    protected $table = "awards";
    protected $fillable = [
        "image",'status','sorting'
    ];

     const ID = "id";
    const IMAGE = "image";
    const POSITION = "sorting";
    const STATUS = "status";
}
