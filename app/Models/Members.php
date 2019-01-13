<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable =["firstname", "surname", "email", "gender", "joined_date"];
    public $timestamps = false;
}
