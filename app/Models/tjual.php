<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tjual extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $fillable = ['id', 'np', 'name', 'wa', 'email', 'tgl', 'tgljual', 'qty', 'totalbayar', 'token', 'status', 'tiket_id'];
}
