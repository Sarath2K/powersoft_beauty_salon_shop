<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    /**
     * Get All Bookings.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $bookings = Booking::with('user', 'payment')->orderBy('created_at', 'desc')->get();
        return view('booking.index', compact('bookings'));
    }

    /**
     * Redirect To Slot Book Page.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        return view('booking.create');
    }

    /**
     * Retrieve Booking Count In Pending Status.
     *
     * @return JsonResponse
     */
    public function bookingCount()
    {
        $bookings = Booking::select(DB::raw('DATE(start_time) as date'), DB::raw('HOUR(start_time) as hour'), DB::raw('COUNT(*) as count'))
            ->whereIn('status', [BOOKING_STATUS_PENDING])
            ->groupBy('date', 'hour')
            ->get();

        $bookingCounts = [];

        foreach ($bookings as $booking) {
            $key = $booking->date . ' ' . sprintf('%02d:00:00', $booking->hour);
            $bookingCounts[$key] = $booking->count;
        }

        return response()->json(
            $bookingCounts
        );
    }

    /**
     * Book The Required Slot By The Customer.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request)
    {
        $bookingData = $request->only(['date', 'fromTime', 'toTime']);

        DB::beginTransaction();
        try {

            $customer = Auth::user();

            if (!$customer || !$customer->hasRole(ROLE_CUSTOMER)) {
                return response()->json([
                    'error' => 'Unauthorized access.'
                ], 403);
            }

            $customerBookingsInCompletedStatus = Booking::where('user_id', $customer->id)
                ->whereNotIn('status', [BOOKING_STATUS_COMPLETED, BOOKING_STATUS_CANCELLED, BOOKING_STATUS_REJECTED])
                ->doesntExist();

            if (!$customerBookingsInCompletedStatus) {
                return response()->json([
                    'error' => 'Please Complete Your Previous Booking'
                ], 400);
            }

            $payment = Payment::create([
                'user_id' => $customer->id,
                'status' => PAYMENT_STATUS_PENDING,
                'method' => PAYMENT_TYPE_CASH,
                'price' => 200,
                'tax_percentage' => null,
                'tax' => null,
                'additional_charge' => 0,
                'total' => 200,
            ]);

            $year = date("Y");
            $bookingId = Booking::getBookingUniqueId($year);

            Booking::create([
                'booking_id' => $bookingId,
                'user_id' => $customer->id,
                'payment_id' => $payment->id,
                'booking_date' => $bookingData['date'],
                'start_time' => $bookingData['fromTime'],
                'end_time' => $bookingData['toTime'],
                'status' => BOOKING_STATUS_PENDING,
                'booking_type' => BOOKING_TYPE_PREBOOKED,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirectUrl' => route('booking.history')
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            logError($exception, 'Error While Booking', 'app/Http/Controllers/BookingController.php');
            return back();
        }
    }

    /**
     * Retrieve The Booking Date.
     *
     * @param string $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show(string $id)
    {
        $booking = Booking::with('user', 'payment')->findOrFail($id);
        return view('booking.show', compact('booking'));
    }

    /**
     * Update The Status To Complete.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function updateCompleted(string $id)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($id);

            $booking->update([
                'status' => BOOKING_STATUS_COMPLETED
            ]);

            DB::commit();
            return redirect()->route('booking');
        } catch (\Exception $exception) {
            DB::rollBack();
            logError($exception, 'Error While Updating Complete Status in Booking', 'app/Http/Controllers/BookingController.php');
            return back();
        }
    }

    /**
     * Update the Status To Rejected
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function updateRejected(string $id)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($id);

            $booking->update([
                'status' => BOOKING_STATUS_REJECTED
            ]);

            DB::commit();
            return redirect()->route('booking');
        } catch (\Exception $exception) {
            DB::rollBack();
            logError($exception, 'Error While Cancel My Booking', 'app/Http/Controllers/BookingController.php');
            return back();
        }
    }

    /**
     * Retrieve the Customer Booking History.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function history()
    {
        $bookings = Booking::with('user', 'payment', 'beautician')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.history', compact('bookings'));
    }

    /**
     * Update the Status To Cancel.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function cancelMyBooking(string $id)
    {
        DB::beginTransaction();
        try {
            $myBooking = Booking::findOrFail($id);

            $myBooking->update([
                'status' => BOOKING_STATUS_CANCELLED
            ]);

            DB::commit();
            return redirect()->route('booking.history');
        } catch (\Exception $exception) {
            DB::rollBack();
            logError($exception, 'Error While Cancel My Booking', 'app/Http/Controllers/BookingController.php');
            return back();
        }
    }
}
