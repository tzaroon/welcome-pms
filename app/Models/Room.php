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
	
	public function isAvailable($roomId , $date) {
		
		$return = DB::select('SELECT
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
				`br`.`room_id` = ' . (int)$roomId .
			' GROUP BY `rmt`.`id`');

		if($return && $return[0] && $return[0]->count > 0) {
			return false;
		}
		return true;
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

	public function getLockPasscode($generateNew = false) {

		$ttlock = new \ttlock\TTLock(env('TTLOCK_CLIENT_ID'), env('TTLOCK_CLIENT_SECRET'));

		$token = $ttlock->oauth2->token(env('TTLOCK_USERNAME'), env('TTLOCK_PASSWORD'),'');

		$ttlock->passcode->setAccessToken($token['access_token']);
		$ttlock->lock->setAccessToken($token['access_token']);

		$locks = $ttlock->lock->list(1,10,time().'000');
dd($locks);
		//$lockResponse = $ttlock->passcode->add( 2316121, '1234', strtotime('2021-05-12 13:00:00').'000', strtotime('2021-05-14 13:00:00').'000', 1, time().'000' );
		$lockResponse = $ttlock->passcode->get( 2316121, 4, strtotime('2021-05-12 13:00:00').'000', strtotime('2021-05-14 13:00:00').'000', 1, time().'000' );
		dd($lockResponse);// Save it to database
	}
}