<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configure extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'email_configuration' => 'object',
    ];

    public function getFormattedPriceAttribute()
    {
        return rtrim(number_format($this->attributes['price_gtf'], 10), '0');
    }
}
