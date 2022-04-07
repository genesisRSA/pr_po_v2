<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategoryItem;

class CategoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name'
    ];

    public function subcategory_items(){
        return $this->hasMany(SubCategoryItem::class, 'category_item_id', 'id');
    }
}
