<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $table = 'trains';

    public function vagons()
    {
        return $this->hasMany(Vagon::class, 'train_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'train_id', 'id');
    }
}
