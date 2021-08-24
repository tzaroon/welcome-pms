@extends('layouts.app')

@section('content')

<a class="btn btn-secondary" style="color:white;"href='/web-check-in/{{$data["bookingCode"]}}/guests'>Check in Online</a><br><br><br>
Hotel Name: {{$data['hotelName']}}<br>
Booking Code: {{$data['bookingCode']}}<br>
Room Name: {{$data['roomName']}}<br>
Total Guests: {{$data['guests']}}<br>

Doors:
<ul>
@foreach ($data['bookingRooms'] as $bookingRoom)
    <li>TTLock Pin: {{ $bookingRoom }},
@endforeach
</ul><br>
Check In: {{$data['booking']['reservation_from']}}<br>
Check Out: {{$data['booking']['reservation_to']}}<br>
Nights: {{$data['booking']['numberOfDays']}}<br>
Price: {{$data['booking']['price']['calendar_price']['price']}}<br>
Vat: {{$data['booking']['price']['calendar_price']['vat']}}<br>
Tax: {{$data['booking']['price']['calendar_price']['tax']}}<br>
Total: {{$data['booking']['price']['calendar_price']['total']}}<br>
Pending: {{$data['booking']['price']['calendar_price']['pending']}}<br>
Daily Prices:
<ul>
@foreach ($data['booking']['price']['price_breakdown']['daily_prices'] as $dailyPrices)
    <li>Date: {{ $dailyPrices['date'] }}, Value: {{ $dailyPrices['value'] }}</li>
@endforeach
</ul><br>
Hotel Terms: {{$data['hotelTerms']}}<br><br>
<img src={{ asset('images/'.$data['hotelImage']) }}><br><br>

            <!-- Button trigger modal -->
            <p>if you want to do the check in on mobile, launch QR code here</p>
<button type="button" style="color:white;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch QR Code
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p class="text-center">Please scan the QR Code to open it</p>
        {{ QrCode::size(300)->generate('https://staging.revroo.io/web-check-in/'.$data['bookingCode']) }}
      </div>
    </div>
  </div>
</div>


<hr>
<hr>



@endsection