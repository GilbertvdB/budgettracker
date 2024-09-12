<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'amount', 
        'rest_amount',
        'max_share_users'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // Define the owner relationship
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function getSharedUsersEmailAttribute()
    {   
        $authUserEmail = Auth::user()->email;

        $emails = $this->users()
                    ->where('email', '!=', $authUserEmail)
                    ->pluck('email');

        $ownerEmail = $this->owner()->where('email', '!=', $authUserEmail)->pluck('email');

        return $ownerEmail->merge($emails)->implode(', ');
    }
}
