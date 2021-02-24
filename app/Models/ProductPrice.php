<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = [
        'company_id',
        'product_id',
        'is_active',
        'price'
    ];

    public function addTaxes($taxes) {

        if($taxes) {
            foreach($taxes as $tax) {
            
                if(!isset($tax['tax_id']))
                    continue;

                $productPricesHasTax = new ProductPricesHasTax();
                $productPricesHasTax->tax_id = $tax['tax_id'];
                $productPricesHasTax->product_price_id = $this->id;
                $productPricesHasTax->amount = array_key_exists('amount', $tax) ? $tax['amount'] : 0;
                $productPricesHasTax->percentage = array_key_exists('percentage', $tax) ? $tax['percentage'] : 0;
                $productPricesHasTax->save();
            }
        }
    }
}
