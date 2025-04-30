<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItinerary extends Model
{
    use HasFactory;

    protected $table = "package_itinerary";

    const ID = "id";
    const DAYS = "days";
    const CITY_ID = "city_id";
    const PACKAGE_MASTER_ID = "package_master_id";
    const STATUS = "status";
    const CREATED_BY = "created_by";
    const UPDATED_BY = "updated_by";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    public function city(){
        return $this->hasOne(CityMaster::class,CityMaster::ID,self::CITY_ID);
    }
}
