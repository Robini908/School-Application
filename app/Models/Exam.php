<?php

namespace App\Models;

use App\Http\Controllers\GradingSystemController;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Exam extends Eloquent
{
    protected $fillable = ['name', 'term', 'year'];

    public function GradingSytems(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
