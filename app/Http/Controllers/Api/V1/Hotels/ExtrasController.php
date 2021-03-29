<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use DB;

class ExtrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id) {

        $user = auth()->user();
        $extras = Extra::where(['company_id' => $user->company_id])->where('hotel_id', $id)
            ->with('product.price.vat')
            ->get();

        $data = $this->paginate($extras);

        return response()->json($data);
    }

    /**
     * Store a new resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $authUser = auth()->user();
        
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'name' => 'required|string|max:191',
            'price' => 'required'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function() use ($authUser, $postData, $request) {
            $settings = $postData['settings'];
            $product = new Product();
            $product->company_id = $authUser->company_id;
            $product->type = 'addon';
            $product->save();
            
            $extra = new Extra();
            $extra->fill($postData);
            $extra->product_id = $product->id;
            $extra->company_id = $authUser->company_id;
            $extra->save();
            
            $taxes[Tax::VAT]['tax_id'] = Tax::VAT;
            $taxes[Tax::VAT]['percentage'] = $postData['tax'] ? : 0; 

            $product->createPrice($postData['price'], $taxes);

            if ($request->hasFile('image')) {
                
                $docPath = $request->file('image')->hashName();
                $request->file('image')->store('public/extras_images');
                $extra->image = $docPath;
                $extra->save();
            }

            $keyedSettings = [];
            if($settings) {
                foreach($settings as $settingId => $setting) {
                    $keyedSettings[$settingId]['extra_setting_id'] = $settingId;
                    $keyedSettings[$settingId]['value'] = $setting;
                }
            }

            $extra->settings()->sync($keyedSettings);
        });

        return response()->json(['message' => 'Accessory added successfully']);
    }
    
    /**
     * Show a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Extra $extra
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Extra $extra) : JsonResponse
    {
        $extra->settings;
        $extra->product->price ? $extra->product->price->vat : null;
        return response()->json($extra);
    }

    /**
     * Update a resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Extra $extra) : JsonResponse
    {
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'name' => 'required|string|max:191',
            'price' => 'required'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function() use ($postData, $request, $extra) {

            $settings = $postData['settings'];

            $extra->fill($postData);
            $extra->update();
            
            $taxes[Tax::VAT]['tax_id'] = Tax::VAT;
            $taxes[Tax::VAT]['percentage'] = $postData['tax'] ? : 0; 

            $extra->product->createPrice($postData['price'], $taxes);

            if ($request->hasFile('image')) {
                
                $docPath = $request->file('image')->hashName();
                $request->file('image')->store('public/extras_images');
                $extra->image = $docPath;
                $extra->save();
            }

            $keyedSettings = [];
            if($settings) {
                foreach($settings as $settingId => $setting) {
                    $keyedSettings[$settingId]['extra_setting_id'] = $settingId;
                    $keyedSettings[$settingId]['value'] = $setting;
                }
            }

            $extra->settings()->sync($keyedSettings);
        });

        return response()->json(['message' => 'Accessory updated successfully']);
    }

    
    /**
     * Destroy a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Model\Extra $extra
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Extra $extra) : JsonResponse
    {
       
        $extra->delete();

        return response()->json(array('message' => 'Accessory deleted successfully'));
    }
}