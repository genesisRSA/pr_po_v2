<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'name',
        'cert_name',
        'cert_image',
        'uploaded_date',
    ];
}
