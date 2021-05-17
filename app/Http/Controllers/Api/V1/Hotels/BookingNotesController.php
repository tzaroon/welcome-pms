<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingNote;
use Illuminate\Http\Request;
use Validator;

class BookingNotesController extends Controller
{
    public function index(Request $request, Booking $booking) {

        return response()->json($booking->notes);
    }

    public function store(Request $request, Booking $booking) {

        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'note' => 'required'
        ], [], [
            'note' => 'Note'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $bookingNote = new BookingNote();
        $bookingNote->fill($postData);
        $bookingNote->booking_id = $booking->id;
        $bookingNote->save();

        return response()->json($bookingNote);
    }

    public function edit(Request $request, Booking $booking, BookingNote $bookingNote) {
        return response()->json($bookingNote);
    }

    public function update(Request $request, Booking $booking, BookingNote $bookingNote) {

        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'note' => 'required'
        ], [], [
            'note' => 'Note'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $bookingNote->fill($postData);
        $bookingNote->save();
        return response()->json($bookingNote);
    }

    public function destroy(Request $request, Booking $booking, BookingNote $bookingNote) {

        $bookingNote->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
