<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMaster extends Model
{
    use HasFactory;

    protected $table = "city_master";

    const TABLE_NAME = "city_master";
    const ID = "id";
    const CITY_NAME = "city_name";
    const STATUS = "status";
    const CREATED_BY = "created_by";
    const UPDATED_BY = "updated_by";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
