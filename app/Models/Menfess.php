<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menfess extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sender()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    public function base()
    {
        return $this->belongsTo(Account::class, 'base_id');
    }
}
