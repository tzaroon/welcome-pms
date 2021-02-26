<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DailyPrice;
use App\Models\Room;
use Illuminate\Http\Request;
use Validator;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = auth()->user();

        $rooms = Room::where('company_id', $user->company_id)->with(
            [
                'roomType'
            ]
        )->whereHas('roomType', function($q) use ($id){
            $q->where('hotel_id', $id);
        })->get();

        return response()->json($rooms);
    }
}