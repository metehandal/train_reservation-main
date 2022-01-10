<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vagon extends Model
{
    use HasFactory;

    protected $table = 'vagons';

    public function train()
    {
        return $this->belongsTo(Train::class, 'id', 'train_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'vagon_id', 'id');
    }
}
