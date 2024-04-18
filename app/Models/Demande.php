<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'libelle_demande',
        'theme',
        'notif_reponse',
    ];

    public function sessionUser(){
        return $this->belongsTo(SessionproUser::class);
    }
}
