<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['customer_id', 'is_active', 'is_subscribed', 'valid_date', 'service_id', 'sub_type'];
}
