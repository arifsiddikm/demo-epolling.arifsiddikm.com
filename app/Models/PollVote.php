<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $fillable = ['poll_id', 'poll_option_id', 'voter_key', 'voter_name', 'ip_address'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }
}
