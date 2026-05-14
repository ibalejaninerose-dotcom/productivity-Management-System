<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayLoss extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'loss_amount',
        'date'
    ];
}