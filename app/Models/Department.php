<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function buildings()
    {
        return $this->belongsToMany(Building::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
