<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = "facilities";
    
    protected $fillable = [
        'icon','title','description','status'
    ];

    const ID = "id";
}
