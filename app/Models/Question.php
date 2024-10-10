<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    public function correctAnswer(): BelongsTo
    {
        return $this->belongsTo(Word::class, 'correct_answer_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('given_answer_id', 'answered_correctly')
            ->withTimestamps();
    }
}
