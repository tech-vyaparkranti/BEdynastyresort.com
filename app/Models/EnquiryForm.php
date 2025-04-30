<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryForm extends Model
{
    use HasFactory;

    protected $table = "enquiries";


    const ID = "id";
    const NAME = "name";
    // const EMAIL = "email";
    const PHONE_NUMBER = "phone_number";
    // const PACKAGE_NAME = "package_name";
    const MESSAGE = "message";
    // const TRAVEL_DATE = "travel_date";
    // const TRAVELLER_COUNT = "traveller_count";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
