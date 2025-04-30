<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLetterEmail extends Model
{
    use HasFactory;

    protected $table = "news_letter_emails";

    const ID = "id";
    const EMAIL_ID = "email_id";
    const IP_ADDRESS = "ip_address";
    const USER_AGENT = "user_agent";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
