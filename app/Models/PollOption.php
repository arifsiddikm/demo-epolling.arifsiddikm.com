<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = ['poll_id', 'name', 'image', 'description', 'order'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }

    public function getPercentageAttribute(): float
    {
        $total = $this->poll->total_votes;
        if ($total === 0) return 0;
        return round(($this->vote_count / $total) * 100, 1);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
