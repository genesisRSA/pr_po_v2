<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseRequestItem;

class SupplierDetails extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    public function supp_detail(){
        return $this->belongsTo(PurchaseRequestItem::class,'purchase_request_item_id');
    }


}
