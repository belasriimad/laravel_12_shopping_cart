<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeSession extends Model
{
    //
    protected $fillable = [
        'stripe_id',
    ];
}
