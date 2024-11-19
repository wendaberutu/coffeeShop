<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'id_order');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
