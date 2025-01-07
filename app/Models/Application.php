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
        'updated_at',
        'cloudways_app_id',
        'app_user',
        'app_password',
        'sys_password',
        'mysql_db_name',
        'mysql_user',
        'mysql_password',
        'webroot',
        'is_csr_available',
        'lets_encrypt',
        'app_version_id',
        'cms_app_id',
        'customer_id',
        'price'
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
