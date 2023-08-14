<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tjual;

class tjual1 extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = ['id', 'tjual_id', 'status', 'validon', 'nourut'];

    public function tjual()
    {
        return  $this->belongsTo(tjual::class, 'tjual_id', 'id');
    }
}
