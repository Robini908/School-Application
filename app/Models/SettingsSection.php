<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingsSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
    
    ];

    /**
     * Define the relationship with the Section model.
     * Each predefined section can be used in multiple classes.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'settings_section_id');
    }
}