<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $fillable = [
        'receipt_id',
        'status',
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
