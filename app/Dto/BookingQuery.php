<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class BookingQuery extends DataTransferObject
{
    public int $hotelId;
    public int $numberOfChildren;
    public int $numberOfAdults;
    public int $nights;
    public string $reservationFrom;
    public string $reservationTo;

    public static function fromRequest($request): self
    {
        return new self([
            'hotelId' => (int)$request['hotel_id'],
            'numberOfChildren' => (int)$request['children_count'],
            'numberOfAdults' => (int)$request['adult_count'],
            'nights' => (int)$request['nights'],
            'reservationFrom' => (string)$request['reservation_from'],
            'reservationTo' => (string)$request['reservation_to']
        ]);
    }
}
