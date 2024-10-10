<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Word extends Model
{
    use HasFactory;

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function correctForQuestion(): HasOne
    {
        return $this->hasOne(Question::class, 'correct_answer_id');
    }
}
