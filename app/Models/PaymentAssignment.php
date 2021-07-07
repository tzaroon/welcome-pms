<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PaymentAssignment extends Model
{
    protected $fillable = [
        'amount',
        'assigned_by',
        'assigned_to',
        'type'
    ];
    
    public function assignedBy() {
        return $this->hasOne(User::class, 'id', 'assigned_by');
    }

    public function assignedTo() {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }
}
