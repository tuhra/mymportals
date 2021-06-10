<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MptCallbackLog extends Model
{
    protected $fillable = ['customer_id', 'reqBody', 'resBody', 'status_code', 'tranid', 'message', 'action', ''];
}
