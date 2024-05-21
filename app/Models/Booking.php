<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'bookings';

    /**
     * @var string[]
     */
    public $fillable = [
        'booking_id',
        'user_id',
        'payment_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'booking_type'
    ];

    /**
     * @param $year
     * @return string
     */
    public static function getBookingUniqueId($year)
    {
        return self::getUniqueId(BOOKING_UNIQUE_ID, BOOKING_UNIQUE_ID_LENGTH, $year);
    }

    /**
     * @param $uniqueIdPattern
     * @param $uniqueIdLength
     * @param $year
     * @return string
     */
    public static function getUniqueId($uniqueIdPattern, $uniqueIdLength, $year)
    {
        $uniqueIdPatternByYear = $uniqueIdPattern . $year;
        $previousId = self::where('booking_id', 'LIKE', $uniqueIdPatternByYear . '%')->orderBy('booking_id', 'desc')->first()->booking_id ?? ($uniqueIdPatternByYear . str_pad(0, $uniqueIdLength, '0', STR_PAD_LEFT));
        if (!$previousId) {
            return $uniqueIdPatternByYear . str_pad(1, $uniqueIdPatternByYear, '0', STR_PAD_LEFT);
        }
        $previousIdNumber = (int)str_replace($uniqueIdPatternByYear, '', $previousId);
        return $uniqueIdPatternByYear . str_pad($previousIdNumber + 1, $uniqueIdLength, '0', STR_PAD_LEFT);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
