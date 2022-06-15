<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function accessCard()
    {
        return $this->hasOne(AccessCard::class);
    }
}
