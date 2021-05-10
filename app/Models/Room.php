<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use DB;

class Room extends Model
{
	protected $guarded = ['company_id'];

	protected $appends = [
		'ocupancy'
	];

	public function roomType() {

		return $this->belongsTo(RoomType::class);
	}
	
	public function getOcupancyAttribute() {

		return $this->roomType->max_people;
	}
	
	public function bookings() {

		return $this->belongsToMany(Booking::class);
	}

	public function avaliability($roomTypeId , $date) {
		
		return DB::select('SELECT 
				`rmt`.`id`,	
				COUNT(*) as `count` 
			FROM `booking_room` as `br`
			JOIN `rate_types` as `rt` ON `rt`.`id` = `br`.`rate_type_id`
			JOIN `room_types` as `rmt` ON `rmt`.`id` = `rt`.`room_type_id`
			LEFT JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
			WHERE 
				`b`.`reservation_from` <= \''.$date.'\'
			AND
				`b`.`reservation_to` > \''.$date.'\'
			AND
				`rmt`.`id` = ' . (int)$roomTypeId .
			' GROUP BY `rmt`.`id`');
	}

	public function booking($id , $date, $todate) {
		
		return DB::select('SELECT 
				`b`.*,
				`br`.`id` as `booking_room_id`,
				`br`.`room_id`,
				`br`.`rate_type_id`,
				`br`.`first_guest_name`
			FROM `booking_room` as `br`
			JOIN `rooms` as `rm` ON `rm`.`id` = `br`.`room_id`
			JOIN `bookings` as `b` ON `b`.`id` = `br`.`booking_id`
			WHERE 
				`b`.`reservation_from` >= \''.$date.'\'
			AND
				`b`.`reservation_from` <= \''.$todate.'\'
			AND
				`br`.`room_id` = ' . (int)$id
		);
	}
}