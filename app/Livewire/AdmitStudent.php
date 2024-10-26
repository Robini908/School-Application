<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;
use App\Models\BloodGroup;
use App\Models\Nationality;
use App\Models\State;
use App\Models\MyClass;
use App\Models\Dorm;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class AdmitStudent extends Component
{
    use WithFileUploads;

    public $first_name, $middle_name, $last_name, $email, $gender, $phone, $dob, $nal_id, $state_id, $lga_id, $bg_id, $photo;
    public $my_class_id, $section_id, $session, $year_admitted, $dorm_id, $dorm_room_no, $house, $inputNumber, $adm_no, $kcpe_marks, $upi_number;
    public $parent_first_name, $parent_middle_name, $parent_last_name, $nin, $parent_phone, $parent_email;
    public $password, $password_confirmation;
    
    public $nationals, $states, $my_classes, $dorms, $bloodGroups;

    public function mount()
    {
        $this->nationals = Nationality::all();
        $this->states = State::all();
        $this->my_classes = MyClass::all();
        $this->dorms = Dorm::all();
        $this->bloodGroups = BloodGroup::all();
    }

    // public function updatedStateId($stateId)
    // {
    //     $this->lga_id = null; // Reset LGA selection

    //     if ($stateId) {
    //         // Assuming you have a function to get LGAs based on state
    //         $this->lgas = LGA::where('state_id', $stateId)->get();
    //     } else {
    //         $this->lgas = [];
    //     }
    // }

    public function submit()
    {
       /* $this->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'gender' => 'required',
            'phone' => 'nullable|string',
            'dob' => 'nullable|date',
            'nal_id' => 'required|exists:nationalities,id',
            'state_id' => 'required|exists:states,id',
            'lga_id' => 'required|exists:lgas,id',
            'bg_id' => 'nullable|exists:blood_groups,id',
            'photo' => 'nullable|image|max:2048',
            'my_class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'session' => 'nullable|string',
            'year_admitted' => 'required|integer',
            'dorm_id' => 'nullable|exists:dorms,id',
            'dorm_room_no' => 'nullable|string',
            'house' => 'nullable|string',
            'inputNumber' => 'nullable|integer',
            'adm_no' => 'nullable|string',
            'kcpe_marks' => 'nullable|integer',
            'upi_number' => 'nullable|string',
            'parent_first_name' => 'required|string|max:255',
            'parent_middle_name' => 'required|string|max:255',
            'parent_last_name' => 'required|string|max:255',
            'nin' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:255',
            'parent_email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $photoPath = $this->photo ? $this->photo->store('photos', 'public') : null;

        StudentRecord::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'nal_id' => $this->nal_id,
            'state_id' => $this->state_id,
            'lga_id' => $this->lga_id,
            'bg_id' => $this->bg_id,
            'photo' => $photoPath,
            'my_class_id' => $this->my_class_id,
            'section_id' => $this->section_id,
            'session' => $this->session,
            'year_admitted' => $this->year_admitted,
            'dorm_id' => $this->dorm_id,
            'dorm_room_no' => $this->dorm_room_no,
            'house' => $this->house,
            'inputNumber' => $this->inputNumber,
            'adm_no' => $this->adm_no,
            'kcpe_marks' => $this->kcpe_marks,
            'upi_number' => $this->upi_number,
            'parent_first_name' => $this->parent_first_name,
            'parent_middle_name' => $this->parent_middle_name,
            'parent_last_name' => $this->parent_last_name,
            'nin' => $this->nin,
            'parent_phone' => $this->parent_phone,
            'parent_email' => $this->parent_email,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'Student admitted successfully.');
        return redirect()->route('students.index');*/
    }

    public function render()
    {
        return view('livewire.admit-student');
    }
}
