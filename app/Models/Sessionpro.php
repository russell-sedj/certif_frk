<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessionpro extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'peut_inscrire',
        'peut_demander',
        'formation_id',
        'lieu_id',
    ];

    public function formation(){
        return $this->belongsTo(Formation::class);
    }

    public function lieu(){
        return $this->belongsTo(Lieu::class);
    }

    public function presences(){
        return $this->hasMany(Presence::class);
    }
    
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('nb_presence', 'note')->using(SessionproUser::class);
    }
}
