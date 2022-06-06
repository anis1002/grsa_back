<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $primaryKey = 'id';

    public $fillable = ['email_receive' , 'email_sender','message'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

}
