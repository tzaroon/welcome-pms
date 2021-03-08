<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * 
     */
    protected $roomTypeId;
    protected $name;

    public function __construct($roomTypeId , $name)
    {        
        $this->roomTypeId = $roomTypeId;
        $this->name = $name;
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
        ->where(['name' => $this->name , 'room_types.id' =>  $this->roomTypeId])
        ->get(); 

        if($query)
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
        return 'The validation error message.';
    }
}
