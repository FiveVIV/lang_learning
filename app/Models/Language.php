<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Language extends Model
{
    use HasFactory;

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }

    public function sets(): MorphMany
    {
        return $this->morphMany(Set::class, 'model');
    }
}
