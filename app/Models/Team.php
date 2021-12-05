<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\LaratrustTeam;

class Team extends LaratrustTeam
{
    use HasFactory;

    public $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
