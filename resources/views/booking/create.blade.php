@extends('layouts.master')
@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Slot Booking
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
                            <div class="col-md-12 p-4">
                                <div id='calendar'></div>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                views: {
                    dayGridMonth: {buttonText: 'Month'},
                    timeGridWeek: {buttonText: 'Week'},
                    timeGridDay: {buttonText: 'Day'}
                },
                slotMinTime: '09:00:00',
                slotMaxTime: '20:00:00',
                businessHours: [
                    {
                        daysOfWeek: [1, 2, 3, 4, 6, 7], // Monday, Tuesday, Wednesday, Thursday, Saturday, Sunday
                        startTime: '09:00', // 9:00 AM
                        endTime: '19:00' // 7:00 PM
                    },
                    {
                        daysOfWeek: [5], // Friday
                        startTime: '00:00', // 12:00 AM
                        endTime: '00:00' // 12:00 AM (making it a holiday)
                    }
                ],
                events: function (fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('booking.counts') }}',
                        method: 'GET',
                        success: function (data) {
                            var events = generateHourlyEvents(data);
                            successCallback(events);
                        },
                        error: function () {
                            failureCallback();
                        }
                    });
                },
                eventClick: function (info) {
                    var eventObj = info.event;
                    var startTime = eventObj.start;
                    var endTime = eventObj.end;

                    Swal.fire({
                        title: 'Confirm Booking',
                        text: 'Do you want to book this slot?\n' +
                            'Date: ' + eventObj.start.toLocaleDateString() + '\n' +
                            'Time: ' + eventObj.start.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}),
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, book it!',
                        cancelButtonText: 'No, cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Retrieve CSRF token from meta tag
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');

                            // Send AJAX request to book the slot
                            $.ajax({
                                url: '{{ route('booking.store') }}',
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                data: JSON.stringify({
                                    date: startTime.toISOString().split('T')[0],
                                    fromTime: startTime.toTimeString().split(' ')[0],
                                    toTime: endTime.toTimeString().split(' ')[0],
                                }),
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire(
                                            'Booked!',
                                            'Your slot has been booked.',
                                            'success'
                                        ).then(() => {
                                            // Redirect to the booking page
                                            window.location.href = data.redirectUrl;
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            data.error || 'There was a problem booking your slot.',
                                            'error'
                                        );
                                    }
                                },
                                error: function (xhr, status, error) {
                                    var errorMessage = xhr.responseJSON?.error || 'There was a problem booking your slot.';
                                    Swal.fire(
                                        'Error!',
                                        errorMessage,
                                        'error'
                                    );
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire(
                                'Cancelled',
                                'Your slot booking has been cancelled.',
                                'error'
                            );
                        }
                    });
                }
            });
            calendar.render();
        });

        function generateHourlyEvents(bookingCounts) {
            var events = [];
            var startHour = 9; // 9:00 AM
            var endHour = 19; // 7:00 PM
            var today = new Date();
            today.setHours(0, 0, 0, 0); // Set time to midnight for comparison
            var now = new Date();

            for (var day = 0; day < 7; day++) { // Loop through each day of the week
                var currentDay = new Date(today);
                currentDay.setDate(today.getDate() + day);

                if (currentDay.getDay() === 5) continue; // Skip Fridays

                for (var hour = startHour; hour < endHour; hour++) {
                    var startTime = new Date(today);
                    startTime.setDate(today.getDate() + day);
                    startTime.setHours(hour, 0, 0, 0);

                    var endTime = new Date(startTime);
                    endTime.setHours(hour + 1, 0, 0, 0);

                    // Only add events for the current date and future dates
                    if (startTime >= now) {
                        var slotKey = `${startTime.toISOString().split('T')[0]} ${startTime.toTimeString().split(' ')[0]}`;
                        var bookingCount = bookingCounts[slotKey] || 0;

                        if (bookingCount < 5) {
                            events.push({
                                title: 'Available Slot',
                                start: startTime,
                                end: endTime
                            });
                        }
                    }
                }
            }
            return events;
        }
    </script>
@endpush
