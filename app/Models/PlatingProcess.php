<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlatingCostUpdate;

class PlatingProcess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function updated_costs(){
        return $this->hasMany(PlatingCostUpdate::class,'plating_process_item_id');
    }
}
