<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materialreservation extends Model
{
    use HasFactory;

    protected $table = 'materialreservations';

    protected $primaryKey = 'id';

    public $fillable = ['reservationdate' , 'teacher_email' , 'timing' , 'material_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function timing()
    {
        return $this->belongsTo(Timing::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
