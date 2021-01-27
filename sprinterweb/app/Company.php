<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = true;

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function isSubscribed(){
        $fechaexpiracion = Carbon::parse($this->subscriptions->last()->finished_at);
        return $fechaexpiracion->greaterThan(Carbon::now());
    }

    public function DaysLeft(){
        $fechaexpiracion = Carbon::parse($this->subscriptions->last()->finished_at);
        return $fechaexpiracion->diffInDays();
    }
}
