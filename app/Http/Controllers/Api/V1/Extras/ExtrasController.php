<?php

namespace App\Http\Controllers\Api\V1\Extras;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ExtrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $user = auth()->user();
        $extras = Extra::where(['company_id' => $user->company_id])->get();

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

       $product = Product::create([
           'company_id' => $authUser->company_id,
           'type' => 'addon',
       ]);

        $extra = new Extra();
        $extra->fill($postData);
        $extra->product_id = $product->id;
        $extra->save();
        
        if ($request->hasFile('image')) {
            
            $docPath = $request->file('image')->hashName();
            $request->file('image')->store('extras_images');
            $extra->image = $docPath;
            $extra->save();
        }

        return response()->json(['message' => 'Accessory added successfully']);
    }
}