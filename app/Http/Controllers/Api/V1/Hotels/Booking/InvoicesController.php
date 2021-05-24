<?php

namespace App\Http\Controllers\API\V1\Hotels\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Validator;

class InvoicesController extends Controller
{
    public function index(Request $request, Booking $booking, $proforma) {

        $invoices = Invoice::where('is_proforma', $proforma)->where('booking_id', $booking->id)->get();

        if($invoices->count() > 0) {

            return response()->json($invoices);
        } else {
            return response()->json(['message' => 'no data found'], 201);
        }
    }

    public function store(Request $request) {

        $user = auth()->user();
        
        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'booking_id' => 'required',
            'issue_date' => 'required',
            'address' => 'required',
        ], [], [
            'booking_id' => 'Booking',
            'issue_date' => 'Issue date',
            'address' => 'Address'
        ]);

        $invoice = new Invoice();
        $invoice->fill($postData);
        $invoice->save();

        if(array_key_exists('product_price_ids', $postData) && $postData['product_price_ids']) {

            $invoice->productPrices()->sync($postData['product_price_ids']);
        }

        return response()->json(array('message' => 'Invoice added successfully.'));
    }
}