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
</style>
{{$data['hotelTerms']}}
<div class="container" style="max-width: 100% !important;">
		<div class="row information_title">
			<div class="col-md-12">
				<a style="text-decoration:none;color:black;"  href='/web-check-in/{{$data["bookingCode"]}}'>
				<i class="fa fa-arrow-left back"></i>
				</a>
				<h4>Información adicional</h4>
		</div>
	</div>
	
	<div class="main_body">
		<h4>Descripción de habitación</h4>
		<div class="row">
			<div class="col-md-12">
				Habitación doble exterior con baño privado y acceso libre
				a las áreas comunes de los huéspedes, cocina y sala de
				estar
			</div>
		</div>
		<hr>
		<h4>Información adicional</h4>
		<div class="row">
			<div class="col-md-12">
				Gracias por reservar en Casa Boutique. Mi nombre es
				Eduardo, y soy el responsable del hotel.
				Casa Boutique se encuetra situada en la Calle Pau Claris,
				145, Barcelona, justo en la esquina con la Calle Valencia.
				El día de antes del checkin, una vez se haya pagado el
				100% de la reserva a través del link que recbirán por
				email, recibirán un código único, que sirve para acceder
				al hotel. Este código se usa en el portal del edificio. Una
				vez dentro, se deben subir unas escaleras hasta el primer
				piso. En la puerta del hotel, se usa el MISMO código. Una
				vez dentro del hotel, se usa el MISMO código en la
				habitación.
				Si tienen cualquier duda, nos pueden contactar en este
				teléfono (whatsapp también): +34.685.160.394
			</div>
		</div>
		<hr>
		<h4>Política de cancelación</h4>
		<div class="row">
			<div class="col-md-12">
				GENIUS Guest services: Daily prices breakdown: VAT:
				13.52 EUR, Included: YES City tax: 9.24 EUR, Included: NO
				Daily prices breakdown: VAT: 13.52 EUR, Included: YES
				City tax: 9.24 EUR, Included: NO
				Commission amount: 44.62
				Double Room, Deposit Policy: The guest will be charged a
				prepayment of the total price of the reservation anytime.
				Cancellation Policy: The guest will be charged the total
				price of the reservation if they cancel anytime.
			</div>
		</div>
	</div>
</div>

@endsection