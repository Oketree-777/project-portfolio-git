<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'content', 'status', 'image', 'category', 
        'description', 'github_url', 'live_url', 'tags', 
        'views', 'featured'
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-project.jpg');
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Check if performance has GitHub URL
     */
    public function hasGithubUrl()
    {
        return !empty($this->github_url);
    }

    /**
     * Check if performance has live URL
     */
    public function hasLiveUrl()
    {
        return !empty($this->live_url);
    }

    /**
     * Get formatted tags
     */
    public function getFormattedTagsAttribute()
    {
        if (!$this->tags) {
            return [];
        }
        return $this->tags;
    }

    /**
     * Scope for featured performances
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope for active performances
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
