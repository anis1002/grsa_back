<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $primaryKey = 'email';

    protected $fillable = ['firstname','lastname','email','phonenumber','department','grade','status','state','password'];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
}
