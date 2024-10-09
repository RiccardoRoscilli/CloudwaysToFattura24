<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'amount',
        'start_date',
        'end_date',
        'payment_type',
        'payment_id',
        'payment_date',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, Application::class, 'id', 'id', 'application_id', 'customer_id');
    }
}


