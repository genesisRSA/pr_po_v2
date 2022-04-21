<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryItem;
use App\Models\SubCategoryItem;
use App\Models\ItemListCostUpdate;

class ItemList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category_item(){
        return $this->belongsTo(CategoryItem::class);
    }

    public function subcategory_item(){
        return $this->belongsTo(SubCategoryItem::class,'sub_category_item_id');
    }

    public function updated_costs(){
        return $this->hasMany(ItemListCostUpdate::class,'item_list_id');
    }
}
