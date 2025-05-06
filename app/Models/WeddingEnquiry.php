<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingEnquiry extends Model
{
    use HasFactory;

    protected $table = "wedding_enquiries";

    protected $fillable = [
        "your_name" ,'partner_name' ,'email' ,'phone' ,'guest_count' ,'wed_date','add_detail'
    ];
}
