<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    use HasFactory;
    protected $fillable = ['nomor', 'otp', 'waktu_kadaluarsa'];
}
