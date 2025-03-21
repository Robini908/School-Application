<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseTenant
{
    protected $fillable = ['id', 'school_name', 'domain'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            // Prevent duplicate domain when creating
            $existingTenant = Tenant::whereHas('domains', function($query) use ($tenant) {
                $query->where('domain', $tenant->domain);
            })->first();

            if ($existingTenant) {
                throw new \Exception('Domain already in use by another tenant.');
            }

            // Generate a unique ID by appending a timestamp or UUID
            $tenant->id = strtolower(str_replace(' ', '-', $tenant->school_name)) . '-' . uniqid();
        });

        static::updating(function ($tenant) {
            // Prevent duplicate domain when updating
            if ($tenant->isDirty('domain')) {
                $existingTenant = Tenant::whereHas('domains', function($query) use ($tenant) {
                    $query->where('domain', $tenant->domain);
                })->where('id', '!=', $tenant->id)->first();

                if ($existingTenant) {
                    throw new \Exception('Domain already in use by another tenant.');
                }
            }

            // Regenerate the ID if the school_name is updated
            if ($tenant->isDirty('school_name')) {
                $tenant->id = strtolower(str_replace(' ', '-', $tenant->school_name)) . '-' . uniqid();
            }
        });
    }

    /**
     * Define the relationship between Tenant and Domain.
     *
     * @return HasMany
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class, 'tenant_id');
    }
}
