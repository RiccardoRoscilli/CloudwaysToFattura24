<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'label', 'status', 'tenant_id', 'backup_frequency', 'backup_retention',
        'local_backups', 'backup_time', 'is_terminated', 'created_at', 'updated_at',
        'platform', 'cloud', 'region', 'instance_type', 'server_fqdn', 'public_ip',
        'volume_size', 'master_user', 'master_password',  'cloudways_server_id',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
