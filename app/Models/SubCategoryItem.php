<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryItem;
use App\Models\ItemList;

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

    public function item_lists(){
        return $this->hasMany(ItemList::class);
    }
}
