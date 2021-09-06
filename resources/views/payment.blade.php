@extends('layouts.app')

@section('content')

<style>
    .information_title{
        background: #ededed;
        padding: 8px 0px 2px;
    }
    .fa-arrow-left{
        font-size: 22px;
        color: #007bff;
        display: inline-block;
        margin-right: 50px;
        cursor: pointer;
    }
    h4{
        display: inline-block;
    }
    .main_body{
        padding: 30px 3%;
    }
    .card{
        width: 95%;
        margin: 0 3%;
        box-shadow: 1px 2px 8px 1px #25232359;
        border: none;
        outline: none;
    }
</style>
<div class="container" style="max-width: 100% !important;">
	<div class="row information_title">
		<div class="col-md-12">
            <a style="text-decoration:none;color:black;" href='/web-check-in/{{$data["bookingCode"]}}/guests'>
                <i class="fa fa-arrow-left back"></i>
            </a>
			<h4>Información del pago</h4>
		</div>
	</div>
	<div class="main_body">
		<div class="card card-1">
			<div class="card-body">
				<div class="row mt-3">
					<div class="col-md-6 col-6">
						<div class="card-text-heading">Daily price</div>
					</div>
					<div class="col-md-6 col-6">
						<div class="card-text">
                            @foreach ($data['dailyPrices'] as $dailyPrices)
                                <p>{{ $dailyPrices['date'] }}: {{ $dailyPrices['value'] }} €</p>
                            @endforeach  
                        </div>
					</div> 
				</div> 
				<div class="row mt-3">
					<div class="col-md-6 col-6">
						<div class="card-text-heading">Price</div>
					</div>
					<div class="col-md-6 col-6">
						<div class="card-text">{{$data['booking']['price']['calendar_price']['price']}} €</div>
					</div> 
				</div> 
				<div class="row mt-3">
					<div class="col-md-6 col-6">
						<div class="card-text-heading">City tax</div>
					</div>
					<div class="col-md-6 col-6">
						<div class="card-text">{{$data['booking']['price']['calendar_price']['tax']}} €</div>
					</div> 
				</div> 
				<div class="row mt-3">
					<div class="col-md-6 col-6">
						<div class="card-text-heading">VAT</div>
					</div>
					<div class="col-md-6 col-6">
						
						<div class="card-text">{{$data['booking']['price']['calendar_price']['vat']}} €</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-6 col-6">
						<div class="card-text-heading">Total</div>
					</div>
					<div class="col-md-6 col-6">
						<div class="card-text">{{$data['booking']['price']['calendar_price']['total']}} €</div>
					</div>
				</div>
				<div class="row mt-3">	
					<div class="col-md-6 col-6">
						<div class="card-text-heading">Pending</div>
					</div> 
					<div class="col-md-6 col-6">
						<div class="card-text">{{$data['booking']['price']['calendar_price']['pending']}} €</div>
					</div>
				</div>
				
				<div class="row mt-5">	
					<div class="col-md-12 col-12">
						<button class="btn btn-info pay_btn" style="width:100%" type="button">Pay</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

