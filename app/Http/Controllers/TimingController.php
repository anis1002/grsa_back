<?php

namespace App\Http\Controllers;



use App\Models\Timing;
use App\Http\Resources\TimingResource;
use Illuminate\Http\Request;

class TimingController extends Controller
{
    public function returntiming()
    {
        $timing = Timing::select('roomtiming','starttime','endtime')->get();

        return 
        // TimingResource::collection(
            $timing;
        // );
       
    }
}
