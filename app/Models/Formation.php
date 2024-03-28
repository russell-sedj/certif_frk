<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Formation extends Model
{
    use HasFactory;
    protected $fillable=[
        "theme", "description"
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
