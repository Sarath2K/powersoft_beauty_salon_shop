@extends('layouts.master')
@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Booking {{ $booking->booking_id }}
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
                                        <tr>
                                            <th>Booking Id</th>
                                            <td class="text-center text-danger"><b>{{ $booking->booking_id }}</b></td>
                                            <th>Customer</th>
                                            <td>{{ $booking->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $booking->user->email }}</td>
                                            <th>Phone</th>
                                            <td>{{ $booking->user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Booked On</th>
                                            <td>{{ $booking->created_at }}</td>
                                            <th>Date</th>
                                            <td>{{ $booking->booking_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Start Time</th>
                                            <td>{{ $booking->start_time }}</td>
                                            <th>End Time</th>
                                            <td>{{ $booking->end_time }}</td>
                                        </tr>
                                        <tr>
                                            <th>Booking Type</th>
                                            <td>{{ $booking->booking_type }}</td>
                                            <th>Status</th>
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
                                        </tr>
                                        <tr>
                                            <th>Beautician</th>
                                            <th>{{ $booking->beautician ? $booking->beautician->name : 'Not Assigned' }}</th>
                                            <th>Amount</th>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <div><i class="mdi mdi-currency-inr"></i></div>
                                                    <div>{{ number_format($booking->payment->price, 2) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Beautician ID</th>
                                            <td>{{ $booking->beautician ? $booking->beautician->customer_id : 'Not Assigned' }}</td>
                                            <th>Additional Charger</th>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <div><i class="mdi mdi-currency-inr"></i></div>
                                                    <div>{{ number_format($booking->payment->additional_charge, 2) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status</th>
                                            <td>
                                                @if($booking->payment->status == PAYMENT_STATUS_PENDING)
                                                    <label
                                                        class="badge badge-gradient-warning w-100">{{ $booking->payment->status }}</label>
                                                @else
                                                    <label
                                                        class="badge badge-gradient-success w-100">{{ $booking->payment->status }}</label>
                                                @endif
                                            </td>
                                            <th>Total Amount</th>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <div><i class="mdi mdi-currency-inr"></i></div>
                                                    <div>{{ number_format($booking->payment->total, 2) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-end">
                                    <button type="button" onclick="history.back()"
                                            class="btn btn-sm btn-primary m-1">
                                        Back
                                    </button>
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
