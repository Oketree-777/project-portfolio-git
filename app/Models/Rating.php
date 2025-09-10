<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'personal_info_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo()
    {
        return $this->belongsTo(PersonalInfo::class);
    }

    /**
     * Get rating as stars
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get rating percentage (0.1-5.0)
     */
    public function getPercentageAttribute()
    {
        return ($this->rating / 5) * 100;
    }

    /**
     * Scope for specific rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for portfolio
     */
    public function scopeForPortfolio($query, $personalInfoId)
    {
        return $query->where('personal_info_id', $personalInfoId);
    }

    /**
     * Scope for user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}