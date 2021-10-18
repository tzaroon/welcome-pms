<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Hotel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function roomTypes() {

        return $this->hasMany(RoomType::class);
    }
    
    public function noRefIdRoomTypes() {

        return $this->hasMany(RoomType::class)->whereNull('ref_id');
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
	        JOIN `hotels` as `h` ON `h`.`id` = `rmt`.`hotel_id`
			JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
			WHERE 
				`b`.`reservation_from` >= \''.$date.'\'
			AND
				`b`.`reservation_from` <= \''.$todate.'\'
			AND
                `h`.`id` = ' . (int)$id . '
            AND
                `br`.`room_id` IS NULL'
		);
	}
    
    public static function sandBoxbookings($id , $date) {
		
		return DB::select('SELECT 
				`b`.`id`,
				`b`.`reservation_from`,
				`b`.`reservation_to`,
				`br`.`id` as `booking_room_id`,
				`br`.`rate_type_id`,
                `rmt`.`id` AS `room_type_id`,
                `rmtd`.`name` AS `room_type_name`,
                `br`.`first_guest_name`
			FROM `booking_room` as `br`
            JOIN `rate_types` as `rt` ON `rt`.`id` = `br`.`rate_type_id`
	        JOIN `room_types` as `rmt` ON `rmt`.`id` = `rt`.`room_type_id`
	        JOIN `room_type_details` as `rmtd` ON `rmt`.`id` = `rmtd`.`room_type_id` AND `rmtd`.`language_id` = \'en\'
	        JOIN `hotels` as `h` ON `h`.`id` = `rmt`.`hotel_id`
			JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
			WHERE 
				`b`.`reservation_from` <= \''.$date.'\'
			AND
				`b`.`reservation_to` > \''.$date.'\'
			AND
                `h`.`id` = ' . (int)$id . '
            AND
                `br`.`room_id` IS NULL'
		);
	}
}
