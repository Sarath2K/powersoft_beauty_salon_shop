<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @param $year
     * @return string
     */
    public static function getCustomerUniqueId($year)
    {
        return self::getUniqueId(CUSTOMER_UNIQUE_ID, CUSTOMER_UNIQUE_ID_LENGTH, $year);
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
        $previousId = self::where('customer_id', 'LIKE', $uniqueIdPatternByYear . '%')->orderBy('customer_id', 'desc')->first()->customer_id ?? ($uniqueIdPatternByYear . str_pad(0, $uniqueIdLength, '0', STR_PAD_LEFT));
        if (!$previousId) {
            return $uniqueIdPatternByYear . str_pad(1, $uniqueIdPatternByYear, '0', STR_PAD_LEFT);
        }
        $previousIdNumber = (int)str_replace($uniqueIdPatternByYear, '', $previousId);
        return $uniqueIdPatternByYear . str_pad($previousIdNumber + 1, $uniqueIdLength, '0', STR_PAD_LEFT);
    }
}
