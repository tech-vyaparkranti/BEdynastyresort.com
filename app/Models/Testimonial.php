<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table= "testimonials";

    protected $fillable = [
        'image',
        'description',
        'name','designation' ,'review' ,'status' ,'sorting'
    ];

    const ID = "id";
    const IMAGE = "image";
    const DESCRIPTION = "description";
    const NAME = "name";
    const DESIGNATION = "designation";
    const REVIEW = "review";
    const STATUS = "status";
    const SORTING = "sorting";
}
