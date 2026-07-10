<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'image',
    ];

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users who have viewed this story.
     */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'story_views')->withTimestamps();
    }

    /**
     * Stories are only "live" for 24 hours, like Instagram.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }
}
