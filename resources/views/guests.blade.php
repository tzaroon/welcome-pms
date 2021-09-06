@extends('layouts.app')

@section('content')
<style>
    .information_title{
        padding: 15px 0px 8px;
        box-shadow: 0px 0px 8px 2px #00000042;
    }
    .fa-arrow-left{
        font-size: 22px;
        color: #007bff;
        display: inline-block;
        cursor: pointer;
        float: left;
        margin-left: 10px;
    }
    h4{
        display: inline-block;
    }
    .main_body{
    padding: 30px 2%;
    }
    .inner_body{
        padding-bottom: 10px;
    }
    .card{
        margin: 20px 0px;
    }
    .inline_flex{
        display: inline-flex;
    }
    .fa-angle-right{
        float: right;
        margin: 15px 0px;
        font-size: 25px;
        color: #007bff;
    }
    .card .input{
        width: 20px;
        height: 20px;	
    }
</style>
<div class="container" style="max-width: 100% !important;">
	<div class="row information_title">
		<div class="col-md-12 text-center">
        <a style="text-decoration:none;color:black;" href='/web-check-in/{{$data["bookingCode"]}}'>
			<i class="fa fa-arrow-left back"></i> 
        </a>
			<h4>Check-in Online</h4>
		</div>
	</div>
	<div class="main_body">
		<div class="inner_body text-center">
			<h4>Bienvenido a la recepción del futuro</h4>
			<div class="row">
				<div class="col-md-12">
					
                Escoge una opción disponible del menu
					inferior, si tus acompañantes ya han hecho el
					checkin online
				</div>
			</div>
		</div>

		<div class="card main-card">
			<div class="card-body">
            <a style="text-decoration:none;color:#444444;" href='/web-check-in/{{$data["bookingCode"]}}/get-booker/{{$data["booker"]["id"]}}'>
				<div class="row">
					<div class="col-md-1 col-2">
						<input type="radio" class="input">
					</div>
					<div class="col-md-8 col-8">
						<h5 class="card-title">Booking by: {{$data['booker']['first_name']}} {{$data['booker']['last_name']}}</h5>
						<div class="card-text">{{$data['status']}}</div>
					</div>
					<div class="col-md-3 col-2">
						<i class="fas fa-angle-right"></i>
					</div>
				</div>
            </a>
			</div>
		</div>

        @for ($i = 0; $i < $data['totalGuests']; $i++)

            @if($data['guests'] && array_key_exists($i, $data['guests']))
                <div class="card card1">
                    <div class="card-body">
                    <a style="text-decoration:none;color:#444444;" href='/web-check-in/{{$data["bookingCode"]}}/get-guest/{{$data["guests"][$i]["id"]}}'>
                        <div class="row">
                            <div class="col-md-1 col-2">
                                <input type="radio" class="input">
                            </div>
                            <div class="col-md-8 col-8">
                                <h5 class="card-title">Guest {{$i+1}}: {{$data['guestUser'][$i]['first_name']}} {{$data['guestUser'][$i]['last_name']}}</h5>
                                <div class="card-text">{{$data['status']}}</div>
                            </div>
                            <div class="col-md-3 col-2">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
            @else
                <div class="card card2">
                    <div class="card-body">
                    <a style="text-decoration:none;color:#444444;" href='/web-check-in/{{$data["bookingCode"]}}/add-guest'>
                        <div class="row">
                            <div class="col-md-1 col-2">
                                <input type="radio" class="input">
                            </div>
                            <div class="col-md-8 col-8">
                                <h5 class="card-title">Guest {{$i+1}}: not added yet</h5>
                                <div class="card-text"></div>
                            </div>
                            <div class="col-md-3 col-2">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
            @endif
        @endfor

        <div class="inner_body text-center">
			<h4>Payment</h4>
			<div class="row">
				<div class="col-md-12">
                    @if($data["bookingDetails"]["payment_status"] == 'not-paid')
                    <p>Payment Status: {{$data["bookingDetails"]["payment_status"]}}</p>
                    <a style="color:white;" href='/web-check-in/{{$data["bookingCode"]}}/payment' class="btn btn-success btn-lg" tabindex="-1" role="button" aria-disabled="true">Make Payment</a>
                    @else
                    <p>Payment Status: {{$data["bookingDetails"]["payment_status"]}}</p>
                    <a href='/web-check-in/{{$data["bookingCode"]}}' class="btn btn-success btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Already Paid</a>
                    @endif
				</div>
			</div>
		</div>

	</div>
</div>


@endsection
