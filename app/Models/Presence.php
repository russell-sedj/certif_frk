<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'statut',
        'nb_participants',
        'sessionpro_id',
    ];

    public function sessionpro(){
        return $this->belongsTo(Sessionpro::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
