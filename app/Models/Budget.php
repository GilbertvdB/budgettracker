<?php

namespace App\Models;

use App\Events\BudgetCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Observers\BudgetObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

// #[ObservedBy([BudgetObserver::class])]
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

    public function pinnedByUsers()
    {
        return $this->belongsToMany(User::class, 'budget_user_pinned')
                    ->withTimestamps();
    }

    public function getPinnedByUserAttribute()
    {
        // Check if the currently authenticated user has pinned this specific budget
        return $this->pinnedByUsers()
                    ->where('user_id', Auth::id())
                    ->where('budget_id', $this->id) // Ensure it's for this specific budget
                    ->exists();
    }

    public function getHasReceiptInReviewAttribute()
    {   
        return $this->receipts()->where('total_verified', 0)->exists() ? 1 : 0;
    }
}
