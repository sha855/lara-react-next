<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['restaurant_id', 'order_amount', 'order_time'];

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
}
