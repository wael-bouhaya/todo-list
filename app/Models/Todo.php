<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [ 
        'title', 
        'description', 
        'completed', 
        'priority', 
    ];

    /**
     * J'ai ajouté cette ligne pour GARANTIR que le champ 'completed' 
     * soit traité comme un booléen (TRUE/FALSE au lieu de 0/1) dans les réponses JSON.
     */
    protected $casts = [
        'completed' => 'boolean',
    ];
}
