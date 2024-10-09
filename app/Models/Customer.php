<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobile',
        'fax',
        'iban',
        'website',
        'note',
        'company_name',
        'fiscal_code',
        'vat_number',
        'billing_street',
        'billing_civic_number',
        'billing_city',
        'billing_province',
        'billing_postal_code',
        'billing_country',
        'sdi_code',
        'pec_email',
        'customer_type'
    ];
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
