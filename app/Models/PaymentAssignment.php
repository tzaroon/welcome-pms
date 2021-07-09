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
    
    protected $appends = [
        'assigned_from_amount',
        'assigned_to_amount',
    ];

    public function assignedBy() {
        return $this->hasOne(User::class, 'id', 'assigned_by');
    }

    public function assignedTo() {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }

    public function getAssignedFromAmountAttribute() {
        $user = auth()->user();
        if($this->assignedTo && $this->assignedTo->id == $user->id) {
            return [
                'formated' => number_format($this->amount, '2', ',', '.'),
                'unformated' => $this->amount
            ];
        } else {
            return null;
        }
    }
    public function getAssignedToAmountAttribute() {
        $user = auth()->user();
        if($this->assignedBy && $this->assignedBy->id == $user->id) {
            return [
                'formated' => number_format($this->amount, '2', ',', '.'),
                'unformated' => $this->amount
            ];
        } else {
            return null;
        }
    }
}
