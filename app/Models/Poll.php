<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poll extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'image', 'slug',
        'primary_key_label', 'primary_key_placeholder', 'is_active',
        'start_date', 'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($poll) {
            if (empty($poll->slug)) {
                $poll->slug = Str::slug($poll->title) . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('order');
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->votes()->count();
    }

    public function hasVoted(string $voterKey): bool
    {
        return $this->votes()->where('voter_key', $voterKey)->exists();
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getUrlAttribute(): string
    {
        return route('poll.show', $this->slug);
    }
}
