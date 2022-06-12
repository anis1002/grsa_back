<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Contact;
use App\Models\Material;
use App\Models\Prsnadministrative;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Waiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{

    public function login(Request $request)
    {


        // return "helllo jfa fa";
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => $request->localestoragePersonType])) {
            $user = auth()->user();
            if ($user->status === "active") {
                //$token = $user->createToken('token');
                return response()->json(["Login" => true, "PersonType" => $user]);
            }
        }
        return ["Login" => false, "PersonType" => ['role' => 'none']];
    }
    // public function userLoginNew(Request $req){

    //     $user= User::where('email',$req->email)->first();

    //     // if(!$user || !($req->password == $user->password))
    //     if(!$user || !Hash::check($req->password, $user->password))
    //     {
    //     return
    //     ["Login"=>false]
    //     ;
    //     }else{

    //         return ["Login"=>true,"PersonType"=>$user];
    //     }

    // }
    //---------------------------------------------------------------------------------------------------------------
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }
    //---------------------------------------------------------------------------------------------------------------
    //generate teachers information
    //---------------------------------------------------------------------------------------------------------------
    public function ShowAllTeachers()
    {
        return response()->json(DB::table('teachers')
            ->select('*')
            ->orderBy('department')->get());
    }

    //---------------------------------------------------------------------------------------------------------------
    public function addteacher(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'PhoneNumber' => 'required|numeric',
                'Departemnt' => 'required',
                'grade' => 'required',
                'status' => 'required',
                'state' => 'required',
                'password' => 'required|min:8'
            ]
        );
        $email = Teacher::whereEmail($request->email)->first();
        if ($validator->fails()) {
            return response()->json('ERROR');
        } elseif (empty($email)) {
            //add teacher in Teachers table
            $teacher = new Teacher();
            $teacher->firstname = $request->input('firstName');
            $teacher->lastname = $request->input('lastName');
            $teacher->email = $request->input('email');
            $teacher->phonenumber = $request->input('PhoneNumber');
            $teacher->department = $request->input('Departemnt');
            $teacher->grade = $request->input('grade');
            $teacher->status = $request->input('status');
            $teacher->state = $request->input('state');
            $password = $request->input('password');
            $teacher->password = Hash::make($password);
            $teacher->save();

            //add teacher in Users table
            $user = new User();
            $user->name = $request->input('firstName');
            $user->email = $request->input('email');
            $password = $request->input('password');
            $user->password = Hash::make($password);
            $user->role = 'Teacher';
            $user->status = $request->input('status');
            $user->save();
            return response()->json('added succesfully');
        } else {
            return response()->json('this email used before');
        }

    }


    public function editteacher(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => 'required',
                'lastName' => 'required',
                'PhoneNumber' => 'required|numeric',
                'Departemnt' => 'required',
                'grade' => 'required',
                'status' => 'required',
                'state' => 'required',
                //'password' => 'min:8'
            ]
        );
        if ($validator->fails()) {
            return response()->json('Error : missing input or Phone Number must be a Number');
        } elseif (strlen($request->password) == 0 || strlen($request->password) >= 8) {
            //$teacher = Teacher::where('email' , $email)->first();
            $teacher = Teacher::whereEmail($request->email)->first();
            $user = User::where('email', $request->email)->first();
            if ($request->password == '') {

                $teacher->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'phonenumber' => $request->PhoneNumber,
                    'department' => $request->Departemnt,
                    'grade' => $request->grade,
                    'status' => $request->status,
                    'state' => $request->state,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    'status' => $request->status,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully');
            } else {
                $teacher->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'phonenumber' => $request->PhoneNumber,
                    'department' => $request->Departemnt,
                    'grade' => $request->grade,
                    'status' => $request->status,
                    'state' => $request->state,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    'status' => $request->status,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully');
            }
        } else {
            return response()->json('Error : Password short than 8 caracters');
        }
    }

    public function deleteteacher(Request $request)
    {
        //$user = DB::select('select email from teachers where id = ?' , [$id]);
        //$teacheremail = Teacher::find($email);
        //$users = DB::select('select id from users where email = ?' , [$email]);
        //$userid = User::where('id', $users);
        $user = DB::table('users')->where('email', $request->Temail)->delete();
        $teacherd = DB::table('teachers')->where('email', $request->Temail)->delete();
        //$users = User::find($email);
        //$users->delete();

        return response()->json('deleted succesfully');
    }
    //---------------------------------------------------------------------------------------------------------------
    //edit profile information
    //---------------------------------------------------------------------------------------------------------------


    //---------------------------------------------------------------------------------------------------------------
    //edit profile admin
    //---------------------------------------------------------------------------------------------------------------
    public function showeadmin(Request $request)
    {
        //$teacher = User::find($id);
        $admin = Administrator::where('email', $request->email)->first();
        return response()->json($admin);
    }
    public function editprofileadmin(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => 'required',
                'lastName' => 'required',
                //'password' => 'min:8'
            ]
        );
        if ($validator->fails()) {
            return response()->json('Error : missing input');
        } elseif (strlen($request->password) == 0 || strlen($request->password) >= 8) {
            //$teacher = Teacher::where('email' , $email)->first();
            $admin = Administrator::whereEmail($request->email)->first();
            $user = User::where('email', $request->email)->first();
            if ($request->password == '') {

                $admin->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully emty');
            } elseif($request->password == $request->password2) {
                $admin->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully');
            }else {
                return response()->json('password mismatch');
            }
        }else{
            return response()->json('Error : Password short than 8 caracters');
        }
    }
    
    


    /*public function Editteacher(Request $request){
    
        $Consultation = consultation::where('id',$Validate['id']);
        $Consultation->update([
            $teacher=


²
            'motive' => $Validate['Reason'],
            'detail' => $Validate['Detail'],
            'examination' => $Validate['Examination'],
            'treatment' => $Validate['Treatment'],
            'type' => $Validate['Type'],
        ]);

        $Response = [
            'message' => 'success',
        ];
        return response($Response,201);

        $validator = Validator::make($request->all(),
            [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'phonenumber' => 'required',
                'department' => 'required',
                'grade' => 'required',
                'status' => 'required',
                'state' => 'required',
                'password' => 'required|min:8'
            ]
        );
    }*/
    //---------------------------------------------------------------------------------------------------------------
    
    //---------------------------------------------------------------------------------------------------------------
    //generate rooms information
    //---------------------------------------------------------------------------------------------------------------
    public function ShowAllRooms()
    {
        return response()->json(Room::orderBy('roomname')->get());
    }
    //---------------------------------------------------------------------------------------------------------------

    public function addroom(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'roomType' => 'required',
                'capacity' => 'required|numeric',
                'floor' => 'required|numeric',
                'type' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('Error Adding Room');
        }else{
            Room::create([
                'roomname' => $request->roomType,
                'capacity' => $request->capacity,
                'floor' => $request->floor,
                'type' => $request->type,
            ]);
            return response()->json('succefully added');

        }
    
    }
    //---------------------------------------------------------------------------------------------------------------
    public function showeditroom($roomname)
    {
        return response()->json(Room::where('roomname', $roomname)->first());
    }
    //---------------------------------------------------------------------------------------------------------------
    public function editroom(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'roomType' => 'required',
                'capacity' => 'required|numeric',
                'floor' => 'required|numeric',
                'type' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('Error Editing Proccess');
        }
        
        $room = Room::where('id', $request->id,)->first();
        $room->update([
            'roomname' => $request->roomType,
            'capacity' => $request->capacity,
            'floor' => $request->floor,
            'type' => $request->type,
        ]);
        return response()->json('Room updated succesfully');
    }
    //---------------------------------------------------------------------------------------------------------------
    public function deleteroom(Request $request)
    {
        $room = DB::table('rooms')->where('id', $request->id)->delete();
        return response()->json('Room deleted succesfully');
    }
    //---------------------------------------------------------------------------------------------------------------
    //generate materials information
    //---------------------------------------------------------------------------------------------------------------
    public function ShowAllmaterials()
    {
        return response()->json(Material::orderBy('typematerial')->get());
    }
    //---------------------------------------------------------------------------------------------------------------

    public function addmaterials(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'state' => 'required',
                'serialnumber' => 'required',
                'property' => 'required',
                'materialtype' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('Error adding Material');
        }
        Material::create([
            'state' => $request->state,
            'serialnumber' => $request->serialnumber,
            'property' => $request->property,
            'typematerial' => $request->materialtype,
        ]);
        return response()->json('succefully added');
    }
    //---------------------------------------------------------------------------------------------------------------
    public function showeditmaterials($id)
    {
        return response()->json(Material::where('id', $id)->first());
    }
    //---------------------------------------------------------------------------------------------------------------
    public function editmaterials(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'state' => 'required',
                'serialnumber' => 'required',
                'property' => 'required',
                'materialtype' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('error');
        }
        //backup
        $material = Material::whereId($request->id)->first();
        $material->update([
            'state' => $request->state,
            'serialnumber' => $request->serialnumber,
            'property' => $request->property,
            'typematerial' => $request->materialtype,
        ]);
        return response()->json('updated succesfully');
    }
    //---------------------------------------------------------------------------------------------------------------
    public function deletematerials(Request $request)
    {
        $material = DB::table('materials')->where('id', $request->id)->delete();
        return response()->json('Material succefully deleted');
    }
    //---------------------------------------------------------------------------------------------------------------
    //this is about consultation
    //---------------------------------------------------------------------------------------------------------------


    public function showreservation(Request $request)
    {
        $reserv =
            DB::table('reservations as r')
            ->select("room_id", "roomname","type")
            ->addSelect(DB::raw("group_concat(case when roomtiming = 8 then (select concat(firstname,' ',lastname) from teachers where email=r.teacher_email) end) as h8"))
            ->addSelect(DB::raw("group_concat(case when roomtiming = 10 then (select concat(firstname,' ',lastname) from teachers where email=r.teacher_email) end) as h10"))
            ->addSelect(DB::raw("group_concat(case when roomtiming = 12 then (select concat(firstname,' ',lastname) from teachers where email=r.teacher_email) end) as h12"))
            ->addSelect(DB::raw("group_concat(case when roomtiming = 14 then (select concat(firstname,' ',lastname) from teachers where email=r.teacher_email) end) as h14"))
            ->Join('rooms', 'r.room_id', '=', 'rooms.id')
            ->where('reservationdate', $request->date)
            ->groupBy("room_id", "roomname", "type")
            ->get();

        return $reserv; //[$reservation,$roomType];
    }
    


    public function showrooms()
    {
        $room = Room::select('id', 'roomname')->get();
        return $room;
    }

    public function AllRequest()
    {
        //$request = Waiting::select('*')->get();
        $request = DB::table('waitings')
        ->select('waitings.id', 'waitings.room_id', 'waitings.reservationdate', 'waitings.teacher_email','timings.starttime', 'timings.endtime', 'rooms.roomname')
        ->join('rooms', 'rooms.id', "=", "room_id")
        ->join("timings", 'waitings.roomtiming', '=', 'timings.roomtiming')
        ->get();
        return $request;
    }
    
    public function DeletRequest(Request $request)
    {
        $room = DB::table('rooms')
        ->select('roomname')
        ->where('id', $request->room_id)
        ->first();
        $wait = DB::table('waitings')
        ->select('waitings.reservationdate', 'timings.starttime', 'timings.endtime')
        ->where('id', $request->id)
        ->join("timings", 'waitings.roomtiming', '=', 'timings.roomtiming')
        ->first();
        Contact::create([
            'email_sender' => 'admin',
            'email_receive' => $request->emailRe,
            'message' => 'your request has been refused for '.$room->roomname.' at '. $wait-> reservationdate . ' In ' . $wait-> starttime . ' - ' . $wait->endtime
        ]);
        $Request = DB::table('waitings')->where('id', $request->id)->delete();
        return response()->json('request succefully deleted');
    }

    public function AcceptRequest(Request $request)
    {
        $room = DB::table('rooms')
        ->select('roomname')
        ->where('id', $request->room_id)
        ->first();
        $wait = DB::table('waitings')
        ->select('waitings.reservationdate', 'timings.starttime', 'timings.endtime')
        ->where('id', $request->id)
        ->join("timings", 'waitings.roomtiming', '=', 'timings.roomtiming')
        ->first();
        Contact::create([
            'email_sender' => 'admin',
            'email_receive' => $request->emailRe,
            'message' => 'your request has been accepted for  ' . $room->roomname . ' at ' . $wait->reservationdate . ' In ' . $wait->starttime . '-' . $wait->endtime
        ]);

        $reservation = Waiting::find($request->id)->first();
        Reservation::create([
            'teacher_email' => $reservation->teacher_email,
            'reservationdate' => $reservation->reservationdate,
            'roomtiming' => $reservation->roomtiming,
            'room_id' => $reservation->room_id,
        ]);
        $foreach = DB::table('waitings')
        ->where('reservationdate', $reservation->reservationdate)
        ->where('roomtiming', $reservation->roomtiming)
        ->where('room_id', $reservation->room_id)
        ->whereNotIn('teacher_email', $reservation)
        ->get();
        $Request = DB::table('waitings')
        ->where('reservationdate', $reservation->reservationdate)
        ->where('roomtiming', $reservation->roomtiming)
        ->where('room_id', $reservation->room_id)
        ->delete();

        foreach ($foreach as $reserv ) {
            Contact::create([
                'email_sender' => 'admin',
                'email_receive' => $reserv->teacher_email,
                'message' => 'your request has been refused for  ' . $room->roomname . ' at ' . $wait->reservationdate . ' from ' . $wait->starttime . ' to ' . $wait->endtime
            ]);

        }
        return response()->json("request succefully accepted");
    }

    public function Deletmessage(Request $request)
    {
        $message = DB::table('contacts')->where('id', $request->id)->delete();
        return response()->json('message succefully deleted');
    }







}




