<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'cloudways_api_key',
        'cloudways_email',
        'fattura24_api_key',
        'test_variable'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
