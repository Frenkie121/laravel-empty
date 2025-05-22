<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'titre', 'description', 'statut', 'date_echeance', 'categorie_id'
    ];

    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
}
