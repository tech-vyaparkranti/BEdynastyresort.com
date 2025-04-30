<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategoriesModel extends Model
{
    use HasFactory;

    protected $table = "package_categories";

    const TABLE_NAME = "package_categories";
    const ID = "id";
    const CATEGORY_NAME = "category_name";
    const PACKAGE_ID = "package_id";
    const POSITION = "position";
    const STATUS = "status";
    const CREATED_BY = "created_by";
    const UPDATED_BY = "updated_by";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    const CATEGORIES = [
        "popular"
    ];
    public function package(){
        return $this->hasOne(PackageMaster::class,PackageMaster::ID,self::PACKAGE_ID)
        ->where(PackageMaster::STATUS,1);
    }
}
