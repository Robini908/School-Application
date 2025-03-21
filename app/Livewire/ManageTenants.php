<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Domain;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ManageTenants extends Component
{
    use WithPagination;

    public $school_name, $domain, $tenant_id;
    public $isOpen = false;

    public function render()
    {
        return view('livewire.manage-tenants', [
            'tenants' => Tenant::with('domains')->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    } 

    private function resetInputFields()
    {
        $this->school_name = '';
        $this->domain = '';
        $this->tenant_id = '';
        $this->resetValidation();
    }

    public function store()
    {
        // Validate inputs
        $this->validate([
            'school_name' => 'required',
            'domain' => 'required|unique:domains,domain',
        ]);

        // Prevent duplicate domain for the tenant
        $existingTenant = Tenant::whereHas('domains', function($query) {
            $query->where('domain', $this->domain);
        })->first();

        if ($existingTenant) {
            session()->flash('error', 'Domain already exists for another tenant.');
            return;
        }

        // Create the tenant
        $tenant = Tenant::create([
            'school_name' => $this->school_name,
        ]);

        // Create the domain in the domains table
        $tenant->domains()->create([
            'domain' => $this->domain,
        ]);

        session()->flash('message', 'Tenant Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tenant = Tenant::with('domains')->findOrFail($id);
        $this->tenant_id = $id;
        $this->school_name = $tenant->school_name;

        // Pre-fill the domain field with the first domain (if it exists)
        $this->domain = $tenant->domains->isNotEmpty() ? $tenant->domains->first()->domain : '';

        $this->openModal();
    }

    public function update()
    {
        // Validate inputs
        $this->validate([
            'school_name' => 'required',
            'domain' => ['required', Rule::unique('domains', 'domain')->ignore($this->tenant_id, 'tenant_id')],
        ]);

        // Get the existing tenant
        $tenant = Tenant::find($this->tenant_id);

        // Update the tenant record (only school_name)
        $tenant->update([
            'school_name' => $this->school_name,
        ]);

        // Update the existing domain if it exists (don't create a new domain)
        $tenantDomain = $tenant->domains->first();
        if ($tenantDomain) {
            $tenantDomain->update(['domain' => $this->domain]);
        }

        session()->flash('message', 'Tenant Updated Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Tenant::find($id)->delete();
        session()->flash('message', 'Tenant Deleted Successfully.');
    }
}
