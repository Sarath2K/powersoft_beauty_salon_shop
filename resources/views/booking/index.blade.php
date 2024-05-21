@extends('layouts.master')
@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Bookings
                </h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <span></span>Overview <i
                                class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                        </li>
                    </ul>
                </nav>
            </div>

            <div>
                <div class="card">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-2 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="table-primary text-center">
                                            <th>Booking Id</th>
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Status</th>
                                            <th>Beautician</th>
                                            <th>Booking Type</th>
                                            <th>Payment Status</th>
                                            <th>Amount</th>
                                            <th>Additional Charger</th>
                                            <th>Total Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td class="text-center">{{ $booking->booking_id }}</td>
                                                <td>{{ $booking->user->name }}</td>
                                                <td>{{ $booking->user->phone }}</td>
                                                <td>{{ $booking->booking_date }}</td>
                                                <td>{{ $booking->start_time }}</td>
                                                <td>{{ $booking->end_time }}</td>
                                                <td>
                                                    @if($booking->status == BOOKING_STATUS_PENDING)
                                                        <label
                                                            class="badge badge-gradient-warning w-100">{{ $booking->status }}</label>
                                                    @elseif($booking->status == BOOKING_STATUS_CANCELLED)
                                                        <label
                                                            class="badge badge-gradient-danger w-100">{{ $booking->status }}</label>
                                                    @elseif($booking->status == BOOKING_STATUS_REJECTED)
                                                        <label
                                                            class="badge badge-gradient-danger w-100">{{ $booking->status }}</label>
                                                    @else
                                                        <label
                                                            class="badge badge-gradient-success w-100">{{ $booking->status }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $booking->beautician ? $booking->beautician->name : 'Not Assigned' }}</td>
                                                <td>{{ $booking->booking_type }}</td>
                                                <td>
                                                    @if($booking->payment->status == PAYMENT_STATUS_PENDING)
                                                        <label
                                                            class="badge badge-gradient-warning w-100">{{ $booking->payment->status }}</label>
                                                    @else
                                                        <label
                                                            class="badge badge-gradient-success w-100">{{ $booking->payment->status }}</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div><i class="mdi mdi-currency-inr"></i></div>
                                                        <div>{{ number_format($booking->payment->price, 2) }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div><i class="mdi mdi-currency-inr"></i></div>
                                                        <div>{{ number_format($booking->payment->additional_charge, 2) }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div><i class="mdi mdi-currency-inr"></i></div>
                                                        <div>{{ number_format($booking->payment->total, 2) }}</div>
                                                    </div>
                                                </td>
                                                <td class="d-flex justify-content-around">
                                                    <div>
                                                        <a type="button" class="btn btn-sm btn-gradient-secondary"
                                                           href="{{ route('booking.show',$booking->id) }}">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <form
                                                            action="{{ route('booking.update.complete', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @if(in_array($booking->status, [BOOKING_STATUS_PENDING]))
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-gradient-success">
                                                                    <i class="mdi mdi-check"></i>
                                                                </button>
                                                            @else
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-gradient-success"
                                                                        disabled>
                                                                    <i class="mdi mdi-check"></i>
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </div>

                                                    <div>
                                                        <form
                                                            action="{{ route('booking.update.reject', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @if(in_array($booking->status, [BOOKING_STATUS_PENDING]))
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-gradient-danger">
                                                                    <i class="mdi mdi-close"></i>
                                                                </button>
                                                            @else
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-gradient-danger" disabled>
                                                                    <i class="mdi mdi-close"></i>
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="15"> No date Found</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © pivottechx.com 2024</span>
                <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a
                        href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
            </div>
        </footer>
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
@endsection
