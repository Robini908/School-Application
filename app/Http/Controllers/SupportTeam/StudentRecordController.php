<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\StudentRecord;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class StudentRecordController extends Controller
{
    protected $loc, $my_class, $user, $student;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
    {
        $this->middleware('teamSA', ['only' => ['edit', 'update', 'reset_pass', 'create', 'store', 'graduated']]);
        $this->middleware('super_admin', ['only' => ['destroy']]);

        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
    }

    public function reset_pass($st_id)
    {
        try {
            $st_id = Qs::decodeHash($st_id);
            $data['password'] = Hash::make('student');
            $this->user->update($st_id, $data);
            return back()->with('flash_success', __('Password reset successfully.'));
        } catch (Exception $e) {
            Log::error("Password reset failed for student ID $st_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while resetting the password: ') . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Fetch necessary data for the view
            /*$data['my_classes'] = $this->my_class->all();
            $data['parents'] = $this->user->getUserByType('parent');
            $data['dorms'] = $this->student->getAllDorms();
            $data['states'] = $this->loc->getStates();
            $data['nationals'] = $this->loc->getAllNationals();*/
 
            // Fetch all student records
            //$data['students'] = StudentRecord::all();

            $mystudents=DB::select("select student_records.*,my_classes.name as classname,sections.name as sectionname,
            parent_details.parent_first_name,parent_details.parent_last_name,parent_details.parent_phone_number
            from student_records join my_classes on student_records.my_class_id=my_classes.id join sections
            on student_records.section_id=sections.id join parent_details on student_records.parent_id=parent_details.parent_id_no");
            
            return view('pages.support_team.students.add', $mystudents);
        } catch (Exception $e) {
            Log::error("Failed to load student creation page: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while loading the creation page: ') . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        //getting all the field from a form request
        //$data = $request->all();
    try
    {
        //parent data
        $parentId = $request->input('id_number');
      //  dd($parentId);
        $parentFname = $request->input('parent_first_name');
        $parentMname = $request->input('parent_middle_name');
        $parentLname = $request->input('parent_last_name');
        $parentPhone = $request->input('parent_phone');
        $parentEmail= $request->input('parent_email');
        $parentPassword = $request->input('parent_password');

        // Check if parent exists
        $parent = DB::table('parent_details')
                    ->where('parent_id_no', $parentId)
                    ->first();
        
        if (!$parent) 
        {
            // Save parent data if not exists
            $parentId = DB::table('parent_details')->insert([
                'parent_id_no' => $parentId,
                'parent_first_name' => $parentFname,
                'parent_middle_name' => $parentMname,
                'parent_last_name' => $parentLname,
                'parent_phone_number' => $parentPhone,
                'parent_email' => $parentEmail,
                'parent_password' => $parentPassword
            ]);
        }
        
        $photo="";
        //upload the image 
        if ($request->hasFile('photo')) 
        {
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $imageName);
            $photo = 'images/' . $imageName;          

        }
                   
        //getting the student data
        $studentFname=$request->input('first_name');
        $studentMname=$request->input('middle_name');
        $studentLname=$request->input('last_name');
        $studentEmail=$request->input('email');
        $studentGender=$request->input('gender');
        $studentPhone=$request->input('phone');
        $studentDob = DateTime::createFromFormat('m/d/Y', $request->input('dob'))->format('Y-m-d');
        $studentNation=$request->input('nal_id');
        $studentState=$request->input('state_id');
        $studentTown=$request->input('town');
        $studentPhoto=$request->$photo;
        $studentClassId=$request->input('my_class_id');
        $studentSectionId=$request->input('section_id');
        $studentYearAdmited=$request->input('year_admitted');
        $studentDormId=$request->input('dorm_id');
        $studentUpi=$request->input('upi_no');
        $studentAdm=$request->input('adm_no');
        $studentBloodGroup=$request->input('bg_id');
        $studentKcpe=$request->input('kcpe_marks');
        $studentPassword=$request->input('password');
       
        //dd($request->input('id_number'));
        // Save student data
        DB::table('student_records')->insert([
            'parent_id' => $request->input('id_number'),
            'my_class_id' => $studentClassId,
            'section_id' => $studentSectionId,
            'dorm_id' =>  $studentDormId,
            'adm_no' => $studentAdm,
            'year_admitted' =>  $studentYearAdmited,
            'first_name' => $studentFname,
            'middle_name' => $studentMname,
            'last_name' => $studentLname,
            'email' =>  $studentEmail,
            'gender' => $studentGender,
            'phone' => $studentPhone,
            'dob' => $studentDob,
            'nal_id' =>$studentNation,
            'state_id' =>$studentState,
            'town' => $studentTown,
            'bg_id' => $studentBloodGroup,
            'photo' => $photo,
            'status' => 'unverified',
            'kcpe'=> $studentKcpe,
            'student_password'=>$studentPassword
        ]);        
        return redirect()->back()->with('flash_success', 'Data saved successfully!');
    } catch (Exception $e) {
        //Log::error("Failed to update student record to not graduated for record ID $sr_id: " . $e->getMessage());
        return back()->with('flash_danger', __('An error occurred while updating the student status: ') . $e->getMessage());
    }
    }

    public function storeCopy(StudentRecordCreate $req)
    {
        try {
            // Collect data from the request
            $data = $req->only(Qs::getUserRecord());
            $sr = $req->only(Qs::getStudentData());

            // Find class type code
            $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;

            // Set user type, name, code, password, and default photo
            $data['user_type'] = 'student';
            $data['name'] = ucwords($req->name);
            $data['code'] = strtoupper(Str::random(10));
            $data['password'] = Hash::make('student');
            $data['photo'] = Qs::getDefaultUserImage();

            // Generate admission number
            // Set username and admission number
            $admissionNumber = '12345'; // Example admission number, should be generated dynamically
            $data['username'] = strtoupper(Qs::getAppCode() . '/' . $ct . '/' . $sr['year_admitted'] . '/' . $admissionNumber);

            // Store photo if provided
            if ($req->hasFile('photo')) {
                $photo = $req->file('photo');
                $f = Qs::getFileMetaData($photo);
                $f['name'] = 'photo.' . $f['ext'];
                $f['path'] = $photo->storeAs(Qs::getUploadPath('student') . $data['code'], $f['name']);
                $data['photo'] = asset('storage/' . $f['path']);
            }

            // Create User
            $user = $this->user->create($data);

            // Assign admission number and user ID to student record
            $admissionNumber = $req->adm_no;
            $sr['adm_no'] = $admissionNumber;
            $sr['user_id'] = $user->id;
            $sr['session'] = Qs::getSetting('current_session');

            // Create Student
            $this->student->createRecord($sr);

            return back()->with('flash_success', __('Student record created successfully!'));
        } catch (Exception $e) {
            Log::error("Failed to store student record: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while creating the student record: ') . $e->getMessage());
        }
    }

    public function listByClass($class_id)
    {
        try {
            $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
            $data['students'] = $this->student->findStudentsByClass($class_id);
            $data['sections'] = $this->my_class->getClassSections($class_id);

            return is_null($mc) ? Qs::goWithDanger() : view('pages.support_team.students.list', $data);
        } catch (Exception $e) {
            Log::error("Failed to list students by class $class_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while listing the students: ') . $e->getMessage());
        }
    }

    public function graduated()
    {
        try {
            $data['my_classes'] = $this->my_class->all();
            $data['students'] = $this->student->allGradStudents();

            return view('pages.support_team.students.graduated', $data);
        } catch (Exception $e) {
            Log::error("Failed to list graduated students: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while listing the graduated students: ') . $e->getMessage());
        }
    }

    public function not_graduated($sr_id)
    {
        try {
            $d['grad'] = 0;
            $d['grad_date'] = NULL;
            $d['session'] = Qs::getSetting('current_session');
            $this->student->updateRecord($sr_id, $d);

            return back()->with('flash_success', __('Student status updated to not graduated.'));
        } catch (Exception $e) {
            Log::error("Failed to update student record to not graduated for record ID $sr_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while updating the student status: ') . $e->getMessage());
        }
    }

    public function show($sr_id)
    {
        try {
            $sr_id = Qs::decodeHash($sr_id);
            if (!$sr_id) {
                return Qs::goWithDanger();
            }

            $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();

            /* Prevent Other Students/Parents from viewing Profile of others */
            if (Auth::user()->id != $data['sr']->user_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($data['sr']->user_id, Auth::user()->id)) {
                return redirect(route('dashboard'))->with('pop_error', __('Access denied.'));
            }

            return view('pages.support_team.students.show', $data);
        } catch (Exception $e) {
            Log::error("Failed to show student record for record ID $sr_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while fetching the student record: ') . $e->getMessage());
        }


    }


    public function details($id)
    {
        $student = StudentRecord::findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit($sr_id)
    {
        try {
            $sr_id = Qs::decodeHash($sr_id);
            if (!$sr_id) {
                return Qs::goWithDanger();
            }

            $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();
            $data['my_classes'] = $this->my_class->all();
            $data['parents'] = $this->user->getUserByType('parent');
            $data['dorms'] = $this->student->getAllDorms();
            $data['states'] = $this->loc->getStates();
            $data['nationals'] = $this->loc->getAllNationals();
            return view('pages.support_team.students.edit', $data);
        } catch (Exception $e) {
            Log::error("Failed to load edit page for student record ID $sr_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while loading the edit page: ') . $e->getMessage());
        }
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        try {
            $sr_id = Qs::decodeHash($sr_id);
            if (!$sr_id) {
                return Qs::goWithDanger();
            }

            $sr = $this->student->getRecord(['id' => $sr_id])->first();
            $d = $req->only(Qs::getUserRecord());
            $d['name'] = ucwords($req->name);

            if ($req->hasFile('photo')) {
                $photo = $req->file('photo');
                $f = Qs::getFileMetaData($photo);
                $f['name'] = 'photo.' . $f['ext'];
                $f['path'] = $photo->storeAs(Qs::getUploadPath('student') . $sr->user->code, $f['name']);
                $d['photo'] = asset('storage/' . $f['path']);
            }

            $this->user->update($sr->user->id, $d); // Update User Details

            $srec = $req->only(Qs::getStudentData());

            $this->student->updateRecord($sr_id, $srec); // Update St Rec

            /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
            Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

            return back()->with('flash_success', __('Student record updated successfully.'));
        } catch (Exception $e) {
            Log::error("Failed to update student record for record ID $sr_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while updating the student record: ') . $e->getMessage());
        }
    }

    public function destroy($st_id)
    {
        try {
            $st_id = Qs::decodeHash($st_id);
            if (!$st_id) {
                return Qs::goWithDanger();
            }

            $sr = $this->student->getRecord(['user_id' => $st_id])->first();
            $path = Qs::getUploadPath('student') . $sr->user->code;
            Storage::exists($path) ? Storage::deleteDirectory($path) : false;
            $this->user->delete($sr->user->id);

            return back()->with('flash_success', __('Student record deleted successfully.'));
        } catch (Exception $e) {
            Log::error("Failed to delete student record for user ID $st_id: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while deleting the student record: ') . $e->getMessage());
        }
    }

    public function dashboard()
    {
        try {
            // Fetch recent student registrations
            $recentStudents = StudentRecord::orderBy('created_at', 'desc')->take(5)->get();

            // Pass the $recentStudents variable to the view
            return view('pages.support_team.dashboard', compact('recentStudents'));
        } catch (Exception $e) {
            Log::error("Failed to load dashboard: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while loading the dashboard: ') . $e->getMessage());
        }
    }
}
