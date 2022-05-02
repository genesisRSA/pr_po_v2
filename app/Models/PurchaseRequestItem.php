<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseRequestList;
use App\Models\SupplierDetails;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pr_list(){
        return $this->belongsTo(PurchaseRequestList::class, 'purchase_request_list_id');
    }

    public function supplier_details(){
        return $this->hasMany(SupplierDetails::class, 'purchase_request_item_id');
    }

}
