<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'payments';

    /**
     * @var string[]
     */
    public $fillable = [
        'user_id',
        'status',
        'method',
        'price',
        'tax_percentage',
        'tax',
        'additional_charge',
        'total',
    ];

    /**
     * @return HasOne
     */
    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}
