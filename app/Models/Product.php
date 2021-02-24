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
}
