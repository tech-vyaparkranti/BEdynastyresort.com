<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestExperience extends Model
{
    use HasFactory;

    protected $table ="guest_experiences";

    protected $fillable = [
        'category' ,'video_link' ,'status' ,
    ];
}
