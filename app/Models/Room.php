<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $primaryKey = 'id';

    protected $fillable = ['capacity','floor','roomname','type'];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

}
