<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurServicesModel extends Model
{
    use HasFactory;

    protected $table = "our_services";

    const ID = "id";
    const SERVICE_NAME = "service_name";
    const SERVICE_DETAILS = "service_details";
    const BANNER_IMAGE = "banner_image";
    const SHORT_DESC = "short_desc";
    const SERVICE_IMAGE = "service_image";
    const POSITION = "position";
    const STATUS = "status";
    const CREATED_BY = "created_by";
    const UPDATED_BY = "updated_by";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const CATEGORY = "category";
}
