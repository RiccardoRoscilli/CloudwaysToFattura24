<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_type',
        'service_id',
        'price',
        'billing_frequency',
        'start_date',
        'end_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'service_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    // App\Models\OrderItem.php

    public function mailbox()
    {
        return $this->belongsTo(Mailbox::class, 'service_id');
    }

}

