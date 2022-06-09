<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Material;
use App\Models\Materialreservation;
use App\Models\Reservation;
use App\Models\Timing;
use App\Models\Room;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Waiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'Teacher'])) {
            $user = auth()->user();
            $token = $user->createToken('token');
            return $token->plainTextToken;
        }

        return response()->json([
            "succes" => false,
            "status" => 200
        ]);
    }
    //---------------------------------------------------------------------------------------------------------------
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }
    //---------------------------------------------------------------------------------------------------------------
    //this is about consultation
    //---------------------------------------------------------------------------------------------------------------

    public function availablerooms(Request $request)
    {
        $timing_id = Timing::find($request->hour);
        $start = $timing_id->starttime;
        $end =  $timing_id->endtime;
        $reserveroom = Reservation::select('room_id')->where('reservationdate', $request->input('date'))
        ->where('roomtiming', '>=', $start)->where('roomtiming', '<', $end)->get();
        $rooms = Room::select('*')->whereNotIn('id', $reserveroom)->get();

        return response()->json($rooms);
    }

    public function showmyreservation(Request $request)
    {

        $reservationtrash = DB::table('reservations')
        ->select('reservations.id','reservations.reservationdate', 'reservations.teacher_email', 'timings.starttime', 'timings.endtime', 'rooms.roomname')
        ->join('rooms', 'rooms.id', "=", "room_id")
        ->join("timings", 'reservations.roomtiming', '=', 'timings.roomtiming')
        ->where('reservationdate', $request->date)
        ->where('teacher_email', $request->email)
        ->get();
        //$roomtype = $reservationtrash;
        return $reservationtrash;
    }




    // public function showreservation(Request $request)
    // {
    //     $rooms = Reservation::select('room_id')->where('teacher_email', $request->input('email'))->get();
    //         $result=[];
    //     foreach ($rooms as $room) {        
    //            $reservation = Room::select('*')->where('id',$room['room_id'])->get();
    //           array_push($result,$reservation);   
    //     }
    //      return response()->json($result);
    // }





    function showRoom(Request $request)
    {
        // return
            //   response()->json(
            // $reservation;
        

    }


    //     public function addreservation(Request $request)
    // {



    // }

    //---------------------------------------------------------------------------------------------------------------
    //this is about reservation
    //---------------------------------------------------------------------------------------------------------------

    public function addreservation(Request $request)
    {
        if ($request->type == 'n') {
            Reservation::create([
                'teacher_email' => $request->email,
                'reservationdate' => $request->date,
                'roomtiming' => $request->hour,
                'room_id' => $request->room_id,
            ]);
            return response()->json('succefully added');
        } else {
            Waiting::create([
                'teacher_email' => $request->email,
                'reservationdate' => $request->date,
                'roomtiming' => $request->hour,
                'room_id' => $request->room_id,
            ]);
            return response()->json('wait for acceptance');
        }
    }


    public function updatereservation(Request $request)
    {
        $reservation = Reservation::where('id', $request->id)->first();
        $reservation->update([
            'reservationdate' => $request->date,
            'roomtiming' => $request->hour,
            'room_id' => $request->room_id,
        ]);
        return response()->json('succefully updated');
    }

    public function deletereservation(Request $request)
    {
        if($request->id==""){
            return response()->json('error');
        }else{

            $reservation = DB::table('reservations')->where('id', $request->id)->delete();
            return response()->json('succefully deleted');
        }
    }

    //---------------------------------------------------------------------------------------------------------------
    //edit profile teacher
    //---------------------------------------------------------------------------------------------------------------
    public function showeteacher(Request $request)
    {
        //$teacher = User::find($id);

        return response()->json(Teacher::where('email', $request->email)->first());
    }

    public function editprofileteacher(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => 'required',
                'lastName' => 'required',
                'phoneNumber' => 'required|numeric',
                //'password' => 'min:8'
            ]
        );
        if ($validator->fails()) {
            return response()->json('missing input');
        } else {
            //$teacher = Teacher::where('email' , $email)->first();
            $teacher = Teacher::whereEmail($request->email)->first();
            $user = User::where('email', $request->email)->first();
            if ($request->password == '') {

                $teacher->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'phonenumber' => $request->phoneNumber,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    //$password => $request->password,
                    //'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully');
            } else {
                $teacher->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'phonenumber' => $request->phoneNumber,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    //$password = $request->password,
                    'password' => Hash::make($request->password),
                ]);
                return response()->json('updated succesfully');
            }
        }
    }
    //---------------------------------------------------------------------------------------------------------------
    //this is about reserve material
    //---------------------------------------------------------------------------------------------------------------

    public function availablematerials(Request $request)
    {
        
        $timing_id = Timing::find($request->hour);
        $start = $timing_id->starttime;
        $end =  $timing_id->endtime;
        $reservematerial = Materialreservation::select('material_id')->where('reservationdate', $request->input('date'))
        ->where('timing', '>=', $start)->where('timing', '<', $end)->get();
        $material = Material::select('*')->whereNotIn('id', $reservematerial)->get();

        return response()->json($material);
    }

    public function addreservationmaterial(Request $request)
    {
        Materialreservation::create([
            'teacher_email' => $request->email,
            'reservationdate' => $request->date,
            'timing' => $request->hour,
            'material_id' => $request->material_id,
        ]);
        return response()->json('succefully added');
    }

    public function updatereservationmaterial(Request $request)
    {
        $reservematerial = Materialreservation::where('id', $request->id)->first();
        $reservematerial->update([
            'reservationdate' => $request->date,
            'timing' => $request->hour,
            'material_id' => $request->material_id,
        ]);
        return response()->json('succefully updated');
    }

    public function deletereservationmaterial(Request $request)
    {
            $reservematerial = DB::table('materialreservations')->where('id', $request->id)->delete();
            return response()->json('succefully deleted');
    }

    public function showmyreservationmaterial(Request $request)
    {

        $reservation = DB::table('materialreservations')
        ->select('materialreservations.id','materialreservations.reservationdate', 'materialreservations.teacher_email', 'materials.typematerial', 'materials.state', 'timings.starttime', 'timings.endtime')
        ->leftJoin('materials', 'material_id', '=', 'materials.id')
        ->join("timings", 'materialreservations.timing', '=', 'timings.roomtiming')
        ->where('reservationdate', $request->date)
        ->where('teacher_email', $request->email)
        ->get();
        //$roomtype = $reservationtrash;
        return response()->json($reservation);
    }


    //---------------------------------------------------------------------------------------------------------------
    //this is about reserve contact
    //---------------------------------------------------------------------------------------------------------------
    public function allteacher()
    {
        //return response()->json(Teacher::orderBy('email')->get());
        return response()->json(DB::table('teachers')
            ->select('*')
            ->orderBy('email')->get());
    }

    public function searchteacher(Request $request)
    {
        $search = DB::table('teachers')
        ->where('lastname','like',$request->name.'%')
        ->select('*')
        ->get();
        return response()->json($search);
    }

    public function sendmessage(Request $request)
    {
        Contact::create([
            'email_sender' => $request->emailSend,
            'email_receive' => $request->emailRe,
            'message' => $request->message,
        ]);
        return response()->json('Sent Succefully');
    }

    public function showmyrecievemessage(Request $request)
    {
        $mymessage = DB::table('contacts')
        ->select('email_sender','message','created_at')
        ->where('email_receive', $request->emailSend)
        // ->orderBy('created_at')
        ->latest()
        ->get();
        return response()->json($mymessage);
    }


}
