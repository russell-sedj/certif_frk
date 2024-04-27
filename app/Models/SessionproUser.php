<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionproUser extends Pivot
{
    //
    protected $table = 'sessionpro_user';

    protected $fillable=[
       'nb_presence',
       'note',
    ];

    public function demandes(){
        return $this->hasMany(Demande::class, 'sessionpro_user_id', 'id');
    }  
}
