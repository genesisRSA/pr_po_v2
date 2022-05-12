<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PurchaseRequestList;
use App\Models\PurchaseRequestItem;
use App\Models\SupplierDetails;

class PurchaseOrderList extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [

    ];

    public function purchase_request(){
        return $this->belongsTo(PurchaseRequestList::class,'pr_id');
    }

    public function suppliers(){
        return $this->hasManyThrough(SupplierDetails::class,
        PurchaseRequestItem::class,
       'purchase_request_list_id',
       'purchase_request_item_id',
       'pr_id',
       'id');
    }

    public function request_items(){
        return $this->hasMany(PurchaseRequestItem::class,'purchase_request_list_id','pr_id');
    }

}
