<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubscriberLog extends Model
{
    protected $fillable = ['customer_id', 'event', 'service_id'];
}
