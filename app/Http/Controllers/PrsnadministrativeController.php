<?php

namespace App\Http\Controllers;

use App\Models\Prsnadministrative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PrsnadministrativeController extends Controller
{

    //---------------------------------------------------------------------------------------------------------------
    //edit profile prsnadministrative
    //---------------------------------------------------------------------------------------------------------------
    public function showeprsnadministrative(Request $request)
    {
        //$teacher = User::find($id);

        return response()->json(Prsnadministrative::where('email', $request->email)->first());
    }
    //---------------------------------------------------------------------------------------------------------------
    public function editprofileprsnadministrative(Request $request)
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
            return response()->json('Error :missing input or Phone Number must be a Number');
        } elseif (strlen($request->password) == 0 || strlen($request->password) >= 8) {
            //$teacher = Teacher::where('email' , $email)->first();
            $prsnadministrative = Prsnadministrative::whereEmail($request->email)->first();
            $user = User::where('email', $request->email)->first();
            if ($request->password == '') {

                $prsnadministrative->update([
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
                return response()->json('updated succesfully');
            } elseif($request->password == $request->password2) {
                $prsnadministrative->update([
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
}
