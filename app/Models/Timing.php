<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    use HasFactory;

    protected $table = 'timings';

    protected $primaryKey = 'roomtiming';

    protected $fillable = ['starttime','endtime','roomtiming'];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
}
