<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'libelle_demande',
        'prete',
    ];

    public function sessionproUser(){
        return $this->belongsTo(SessionproUser::class);
    }
}
