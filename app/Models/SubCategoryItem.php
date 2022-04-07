<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryItem;

class SubCategoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_item_id',
        'subcategory_name'
    ];


    public function category_item()
    {
        return $this->belongsTo(CategoryItem::class);
    }
}
