<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\PurchaseRequestItem;
use App\Models\SupplierDetails;
use App\Models\PurchaseOrderList;

class PurchaseRequestList extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pr_items(){
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function suppliers(){
        return $this->hasManyThrough(SupplierDetails::class,
                                     PurchaseRequestItem::class,
                                    'purchase_request_list_id',
                                    'purchase_request_item_id',
                                    'id',
                                    'id');
    }

    public function purchase_order(){
        return $this->hasOne(PurchaseOrderList::class, 'pr_id');
    }
}
