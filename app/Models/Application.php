<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'label',
        'application',
        'app_version',
        'app_fqdn',
        'sys_user',
        'cname',
        'server_id',
        'created_at',
        'cloudways_app_id'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
