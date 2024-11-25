<?php

namespace App\Livewire;

use Livewire\Component;



class AllSubjectManagementActions extends Component
{

    public $activeAction = null;

    public function render()
    {
        return view('livewire.all-subject-management-actions');
    }
}
