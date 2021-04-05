<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const TYPE_ROOM = 'room';

    public function createPrice($price, $taxes = []) {

        $productPrice = ProductPrice::firstOrNew(['company_id' => $this->company_id, 'product_id' => $this->id, 'is_active' => 1, 'price' => $price]);

        if(!$productPrice->id) {
            ProductPrice::where('product_id', $this->id)->update(['is_active' => 0]);
        }
        
        $productPrice->save();

        if($taxes) {
            $productPrice->addTaxes($taxes);
        }
        return $productPrice;
    }

    public function getPrice() {
        return ProductPrice::where(['company_id' => $this->company_id, 'product_id' => $this->id, 'is_active' => 1])->first()->price;
    }
    
    public function price() {
        return $this->hasOne(ProductPrice::class)->where('is_active', 1);
    }

    public function extra() {
        return $this->hasOne(Extra::class);
    }
    
    public function dailyPrice() {
        return $this->hasOne(DailyPrice::class);
    }
}
