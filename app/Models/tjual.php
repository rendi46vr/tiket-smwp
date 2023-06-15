<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tjual1;

class tjual extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $fillable = ['id', 'np', 'name', 'wa', 'email', 'tgl', 'tgljual', 'qty', 'totalbayar', 'token', 'status', 'tiket_id'];


    public function tjual1()
    {
        return  $this->hasMany(tjual1::class);
    }
}
