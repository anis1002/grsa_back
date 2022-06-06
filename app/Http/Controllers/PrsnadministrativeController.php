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
    public function login(Request $request)
    {
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password, 'role'=>'AdministrativePerson']))
        {
            $user = auth()->user();
            $token = $user->createToken('token');
            return $token->plainTextToken;
        }
        return response()->json([
            "succes"=>false,
            "status"=>200
        ]);
    }

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
            return response()->json('missing input');
        } else {
            //$teacher = Teacher::where('email' , $email)->first();
            $prsnadministrative = Prsnadministrative::whereEmail($request->email)->first();
            $user = User::where('email', $request->email)->first();
            if ($request->Password == '') {

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
            } else {
                $prsnadministrative->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    //$password = $request->password,
                    'password' => Hash::make($request->Password),
                ]);
                $user->update([
                    'name' => $request->firstName,
                    //$password = $request->password,
                    'password' => Hash::make($request->Password),
                ]);
                return response()->json('updated succesfully');
            }
        }
    }
}
