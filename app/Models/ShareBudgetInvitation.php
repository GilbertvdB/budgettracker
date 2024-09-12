<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareBudgetInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'from_user', 
        'to_user', 
        'to_email', 
        'status', 
        'token',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
