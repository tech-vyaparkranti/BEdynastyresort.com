<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WedVenuse extends Model
{
    use HasFactory;

    protected $table = "wed_venuses";
    protected $fillable = [
        'icon','title','description','status'
    ];
    const ID = "id";
}
