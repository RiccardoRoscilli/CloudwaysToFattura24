<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudwaysService extends Model
{
    use HasFactory;
    protected $fillable = ['service_name', 'service_type', 'price'];
}
