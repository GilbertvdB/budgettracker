<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $fillable = [
        'image_url',
        'ocr_text',
        'receipt_id',
        'status',
        'total',
    ];
}
