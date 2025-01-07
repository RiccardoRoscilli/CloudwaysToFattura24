<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'expiry_date',
        'is_active', // Aggiunto
        'customer_id',
        'service_name',
        'service_type',
        'billing_frequency',
        'is_active',
        'authinfo',
        'status'

    ];
    // Relazione con Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relazione con OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'service_id');
    }

    // Relazione con Server
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

}
