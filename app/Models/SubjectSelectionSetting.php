<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectSelectionSetting extends Model
{
    protected $fillable = ['class_id', 'is_subject_selection_enabled','deadline'];

    public function myClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }
}
