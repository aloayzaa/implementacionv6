<?php

namespace App\PaymentType\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $table = "mediopago";
    public $timestamps = false;
}
