<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\Booker;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Guest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnumsController extends Controller
{
    public function bookingSources(Request $request) : JsonResponse
    {
        return response()->json(Booking::$__sources);
    }
   
    public function bookingStartTimes(Request $request) : JsonResponse
    {
        $formatter = function ($time) {
            if ($time % 3600 == 0) {
                return date('H:i', $time);
            } else {
                return date('H:i', $time);
            }
        };
        $halfHourSteps = range(0, 47*1800, 1800);
        $halfHourSteps = array_combine($halfHourSteps, $halfHourSteps);

        return response()->json(array_map($formatter, $halfHourSteps));
    }

    public function bookingStatus(Request $request) : JsonResponse
    {
        return response()->json(Booking::$__status_array);
    }
    
    public function bookingPaymentStatus(Request $request) : JsonResponse
    {
        return response()->json(Booking::$__payment_status_array);
    }
    
    public function guestTypes(Request $request) : JsonResponse
    {
        return response()->json(Guest::$__guest_types_array);
    }
    
    public function documentTypes(Request $request) : JsonResponse
    {
        return response()->json(Booker::$__document_types_array);
    }
    
    public function genders(Request $request) : JsonResponse
    {
        return response()->json(User::$__gender_array);
    }
}