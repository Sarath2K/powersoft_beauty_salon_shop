<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;

class AssignBeautician extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-beautician';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It Will Auto Assign The Beautician To The Booking.';

    /**
     * It Will Auto Assign The Beautician To The Booking.
     *
     * Execute the console command.
     */
    public function handle()
    {
        $unassignedBookings = Booking::where('status', BOOKING_STATUS_PENDING)->whereNull('beautician_id')->get();

        foreach ($unassignedBookings as $booking) {
            $beautician = $this->findAvailableBeautician($booking);

            if ($beautician) {
                $booking->beautician_id = $beautician->id;
                $booking->save();
            }
        }
    }

    /**
     * @param $booking
     * @return mixed|null
     */
    private function findAvailableBeautician($booking)
    {
        $beauticians = User::role(ROLE_BEAUTICIAN)->get();

        foreach ($beauticians as $beautician) {
            if ($this->isBeauticianAvailable($beautician, $booking)) {
                return $beautician;
            }
        }

        return null;
    }

    /**
     * @param $beautician
     * @param $booking
     * @return bool
     */
    private function isBeauticianAvailable($beautician, $booking)
    {
        $conflictingBookings = Booking::where('beautician_id', $beautician->id)
            ->where('booking_date', $booking->booking_date)
            ->where(function ($query) use ($booking) {
                $query->where(function ($q) use ($booking) {
                    $q->where('start_time', '<', $booking->end_time)->where('end_time', '>', $booking->start_time);
                });
            })
            ->exists();

        return !$conflictingBookings;
    }
}
