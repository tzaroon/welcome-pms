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

                $productPricesHasTax = ProductPricesHasTax::firstOrNew([
                    'tax_id' => $tax['tax_id'],
                    'product_price_id' => $this->id,
                    'is_active' => 1,
                    'amount' => array_key_exists('amount', $tax) ? $tax['amount'] : 0,
                    'percentage' => array_key_exists('percentage', $tax) ? $tax['percentage'] : 0,
                ]);

                if(!$productPricesHasTax->created_at) {
                   
                    ProductPricesHasTax::where([
                        'tax_id' => $tax['tax_id'],
                        'product_price_id' => $this->id
                    ])->update(['is_active' => 0]);
                    
                    $productPricesHasTax = new ProductPricesHasTax();
                    $productPricesHasTax->fill([
                        'tax_id' => $tax['tax_id'],
                        'product_price_id' => $this->id,
                        'amount' => array_key_exists('amount', $tax) ? $tax['amount'] : 0,
                        'percentage' => array_key_exists('percentage', $tax) ? $tax['percentage'] : 0,
                        'is_active' => 1
                    ]);
                }

                $productPricesHasTax->save();
            }
        }
    }

    public function taxes() {
        
        return $this->hasMany(ProductPricesHasTax::class)->where('is_active', 1);
    }
    
    public function vat() {
        
        return $this->hasOne(ProductPricesHasTax::class)->where('is_active', 1)->where('tax_id', 3);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
