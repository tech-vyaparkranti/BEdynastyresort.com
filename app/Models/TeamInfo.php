<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "team_infos";

    protected $fillable = [
        'image','name','designation','instagram_link' ,'linkedin_link' ,'facebook_link' ,'twitter_link' ,'position','status'
    ];

    const ID = 'id';
    const IMAGE = "image";
    const NAME = "name";
    const DESIGNATION = "designation";
    const POSITION = "position";
    const INSTAGRAM = "instagram_link";
    const FACEBOOK = "facebook_link";
    const TWITTER = "twitter_link";
    const LINKEDIN = "linkedin_link";
    const STATUS = "status";
}
