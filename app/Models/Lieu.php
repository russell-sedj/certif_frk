<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lieu extends Model
{
    use HasFactory;
    protected $fillable=["ville","addresse",];

    public function sessionpros(){
        return $this->hasMany(Sessionpro::class);
    }
}
