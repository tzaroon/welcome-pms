<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class RoomType extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function roomTypeDetail() {

        $language = Language::where(['is_default' => 1])->first();
        return $this->hasOne(RoomTypeDetail::class)->where('language_id', $language->id);
    }
    
    public function roomTypeDetails() {

        return $this->hasMany(RoomTypeDetail::class);
    }

    public function images() {

        return $this->hasMany(RoomTypeImage::class);
    }
    
    public function hotel() {

        return $this->belongsTo(Hotel::class);
    }
    
    public function category() {

        return $this->belongsTo(RoomCategory::class);
    }
    
    public function rateTypes() {

        return $this->hasMany(RateType::class)->with('detail');
    }
   
    public function noRefRateTypes() {

        return $this->hasMany(RateType::class)->whereNull('ref_id')->with('detail');
    }

    public function rooms() {
        return $this->hasMany(Room::class);
    }

    public function getAvailableRoom($date) {

        foreach($this->rooms as $room) {
            $booked = DB::select('SELECT
                    COUNT(*) as `count` 
                FROM `booking_room` as `br`
                LEFT JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
                WHERE 
                    `b`.`reservation_from` <= \''.$date.'\'
                AND
                    `b`.`reservation_to` > \''.$date.'\'
                AND
                    `br`.`room_id` = ' . (int)$room->id);
            if($booked && $booked[0]->count == 0) {
                return $room;
            }
        }
        return null;
    }
    public function getAvailableRooms($date) {

        $availableRooms = [];
        foreach($this->rooms as $room) {
            $booked = DB::select('SELECT
                    COUNT(*) as `count` 
                FROM `booking_room` as `br`
                LEFT JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
                WHERE 
                    `b`.`reservation_from` <= \''.$date.'\'
                AND
                    `b`.`reservation_to` > \''.$date.'\'
                AND
                    `br`.`room_id` = ' . (int)$room->id);
            if($booked && $booked[0]->count == 0) {
                $availableRooms[] = $room;
            }
        }

        return $availableRooms;
    }

    public function booking($id , $date, $todate) {
		
		return DB::select('SELECT 
				`b`.*,
				`br`.`id` as `booking_room_id`,
				`br`.`room_id`,
				`br`.`rate_type_id`,
				`br`.`first_guest_name`
			FROM `booking_room` as `br`
            JOIN `rate_types` as `rt` ON `rt`.`id` = `br`.`rate_type_id`
	        JOIN `room_types` as `rmt` ON `rmt`.`id` = `rt`.`room_type_id`
			JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
			WHERE 
				`b`.`reservation_from` >= \''.$date.'\'
			AND
				`b`.`reservation_from` <= \''.$todate.'\'
			AND
                `rmt`.`id` = ' . (int)$id . '
            AND
                `br`.`room_id` IS NULL'
		);
	}
}
