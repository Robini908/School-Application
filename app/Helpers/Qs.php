<?php

namespace App\Helpers;

use Hashids\Hashids;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\UserType;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Qs
{

    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    public function hashRoute($route, $id)
    {
        // Using Laravel's built-in hashing functionality
        $hashedId = Hash::make($id);

        // Using Hashids\Hashids to generate a unique identifier
        $hashedRoute = $this->hashids->encode($route, $hashedId);

        return $hashedRoute;
    }
    public static function displayError($errors)
    {
        $errorMessages = '';
        foreach ($errors as $err) {
            $errorMessages .= '<li>' . $err . '</li>';
        }

        return '
            <div id="error-alert" class="alert alert-danger alert-styled-left alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-semibold">Oops!</span> 
                <ul class="mb-0">' .
            $errorMessages . '
                </ul>
            </div>
        ';
    }

    public static function displaySuccess($msg)
    {
        return '
            <div id="success-alert" class="alert alert-success alert-bordered alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>' .
            $msg .
            '</div>
        ';
    }

    public static function getAppCode()
    {
        return self::getSetting('system_title') ?: 'MBUKU';
    }

    public static function getDefaultUserImage()
    {
        return asset('global_assets/images/user.png');
    }

    public static function getPanelOptions()
    {
        return '    <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>';
    }

    public static function getTeamSA()
    {
        return ['admin', 'super_admin'];
    }

    public static function getTeamAccount()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function getTeamSAT()
    {
        return ['admin', 'super_admin', 'teacher'];
    }

    public static function getTeamAcademic()
    {
        return ['admin', 'super_admin', 'teacher', 'student', 'parent'];
    }



    public static function hash($id)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        return $hash->encode($id);
    }

    public static function unhash($hashedId)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($hashedId);
        return $decoded ? $decoded[0] : null;
    }

    public static function getUserRecord($remove = [])
    {
        $data = ['first_name', 'middle_name', 'last_name', 'email', 'phone', 'dob', 'gender', 'address', 'bg_id', 'nal_id', 'state_id', 'lga_id'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Fetch staff record fields
    public static function getStaffRecord($remove = [])
    {
        $data = ['emp_date'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Fetch student data fields
    public static function getStudentData($remove = [])
    {
        $data = ['my_class_id', 'section_id', 'my_parent_id', 'dorm_id', 'dorm_room_no', 'adm_no', 'year_admitted', 'wd', 'wd_date', 'grad', 'grad_date', 'house', 'age'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Decode a hash string
    public static function decodeHash($str, $toString = true)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($str);
        return $toString ? implode(',', $decoded) : $decoded;
    }

    // Check if the user is a team account
    public static function userIsTeamAccount()
    {
        return self::userHasRole('accountant');
    }

    // Check if the user is a super admin
    public static function userIsTeamSA(): bool
    {
        return self::userHasRole('super_admin');
    }

    // Check if the user is a team SAT
    public static function userIsTeamSAT()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher']);
    }

    // Check if the user is academic staff
    public static function userIsAcademic()
    {
        return self::userHasRole(['teacher', 'librarian']);
    }

    // Check if the user is administrative staff
    public static function userIsAdministrative()
    {
        return self::userHasRole(['admin', 'super_admin', 'accountant']);
    }

    // Check if the user is an admin
    public static function userIsAdmin()
    {
        return self::userHasRole('admin');
    }

    // Get the user's type
    public static function getUserType()
    {
        $user = Auth::user();
        return $user ? $user->userType->title ?? null : null;
    }

    // Check if the user is a super admin
    public static function userIsSuperAdmin()
    {
        return self::userHasRole('super_admin');
    }

    // Check if the user is a student
    public static function userIsStudent()
    {
        return self::userHasRole('student');
    }

    // Check if the user is a teacher
    public static function userIsTeacher()
    {
        return self::userHasRole('teacher');
    }

    // Check if the user is a parent
    public static function userIsParent()
    {
        return self::userHasRole('parent');
    }



    // Check if the user is staff (admin, teacher, accountant, etc.)
    public static function userIsStaff()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher', 'accountant', 'librarian']);
    }

    // Get staff roles
    public static function getStaff($remove = [])
    {
        $data = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Get all user types dynamically from the database
    public static function getAllUserTypes($remove = [])
    {
        $userTypes = UserType::pluck('title')->toArray();
        return $remove ? array_values(array_diff($userTypes, $remove)) : $userTypes;
    }

    // Check if the user is the head super admin (untouchable)
    public static function headSA(int $user_id)
    {
        return $user_id === 1;
    }

    // Check if the user is part of the PTA
    public static function userIsPTA()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher', 'parent']);
    }

    // Check if a student belongs to a parent
    public static function userIsMyChild($student_id, $parent_id)
    {
        return StudentRecord::where('user_id', $student_id)->where('my_parent_id', $parent_id)->exists();
    }

    // Get administrative team roles
    public static function getTeamAdministrative()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    // Get PTA roles
    public static function getPTA()
    {
        return ['super_admin', 'admin', 'teacher', 'parent'];
    }

    // Get student record by user ID
    public static function getSRByUserID($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    // Helper method to check if the user has a specific role
    protected static function userHasRole($roles)
    {
        $user = Auth::user();
        if (!$user || !$user->userType) {
            return false;
        }

        $userRole = $user->userType->title;
        return in_array($userRole, (array)$roles);
    }

    public static function getPublicUploadPath()
    {
        return 'uploads/';
    }

    public static function getUserUploadPath()
    {
        return 'uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
    }

    public static function getUploadPath($user_type)
    {
        return 'uploads/' . $user_type . '/';
    }

    public static function getFileMetaData($file)
    {
        //$dataFile['name'] = $file->getClientOriginalName();
        $dataFile['ext'] = $file->getClientOriginalExtension();
        $dataFile['type'] = $file->getClientMimeType();
        $dataFile['size'] = self::formatBytes($file->getSize());
        return $dataFile;
    }

    public static function generateUserCode()
    {
        return substr(uniqid(mt_rand()), -7, 7);
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public static function getSetting($type)
    {
        return Setting::where('type', $type)->first()->description;
    }


    public static function getCurrentSession()
    {
        return self::getSetting('current_session');
    }

    public static function getNextSession()
    {
        $oy = self::getCurrentSession();
        $old_yr = explode('-', $oy);
        return ++$old_yr[0] . '-' . ++$old_yr[1];
    }

    public static function getSystemName()
    {
        return self::getSetting('system_name');
    }

    public static function findMyChildren($parent_id)
    {
        return StudentRecord::where('my_parent_id', $parent_id)->with(['user', 'my_class'])->get();
    }

    public static function findTeacherSubjects($teacher_id)
    {
        return Subject::where('teacher_id', $teacher_id)->with('my_class')->get();
    }

    public static function findStudentRecord($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getMarkType($class_type)
    {
        switch ($class_type) {
            case 'J':
                return 'junior';
            case 'S':
                return 'senior';
            case 'N':
                return 'nursery';
            case 'P':
                return 'primary';
            case 'PN':
                return 'pre_nursery';
            case 'C':
                return 'creche';
        }
        return $class_type;
    }

    public static function json($msg, $ok = TRUE, $arr = [])
    {
        return $arr ? response()->json($arr) : response()->json(['ok' => $ok, 'msg' => $msg]);
    }

    public static function jsonStoreOk()
    {
        return self::json(__('msg.store_ok'));
    }

    public static function jsonUpdateOk()
    {
        return self::json(__('msg.update_ok'));
    }

    public static function storeOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.store_ok'));
    }

    public static function deleteOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.del_ok'));
    }

    public static function updateOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.update_ok'));
    }

    public static function goToRoute($goto, $status = 302, $headers = [], $secure = null)
    {
        $data = [];
        $to = (is_array($goto) ? $goto[0] : $goto) ?: 'dashboard';
        if (is_array($goto)) {
            array_shift($goto);
            $data = $goto;
        }
        return app('redirect')->to(route($to, $data), $status, $headers, $secure);
    }

    public static function goWithDanger($to = 'dashboard', $msg = NULL)
    {
        $msg = $msg ? $msg : __('msg.rnf');
        return self::goToRoute($to)->with('flash_danger', $msg);
    }

    public static function goWithSuccess($to, $msg)
    {
        return self::goToRoute($to)->with('flash_success', $msg);
    }

    public static function getDaysOfTheWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}
