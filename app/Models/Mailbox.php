<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Mailbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'server',
        'IMAPport',
        'SMTPport',
        'customer_id'
    ];

    // Definisci la relazione con il model Customer (molti a uno)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
