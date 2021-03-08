<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class DuplicateRateTypeValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * 
     */
    protected $roomTypeId;
    protected $id;

    public function __construct($roomTypeId , $id=null)
    {        
        $this->roomTypeId = $roomTypeId;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = DB::table('room_types')        
            ->join('rate_types','room_types.id','=','rate_types.room_type_id')
            ->join('rate_type_details','rate_type_details.rate_type_id','=','rate_types.id')
            ->where('name', $value)
            ->where('room_types.id', $this->roomTypeId);
        
        if($this->id) {

            $query->where('rate_types.id', '!=', $this->id);
        }

        if($query->count())
        {
            return false;            
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Duplicate rate type.';
    }
}
