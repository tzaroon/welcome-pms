<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'prefix',
        'issue_date',
        'guest_name',
        'vat_number',
        'phone',
        'address',
        'city',
        'zip_code',
        'state',
        'country',
        'observations',
        'notes',
        'apply_tourist_tax'
    ];

    public function productPrices() {
        return $this->belongsToMany(ProductPrice::class, 'invoice_has_product_prices');
    }
}
