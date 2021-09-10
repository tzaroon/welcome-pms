@extends('layouts.app')

@section('content')

<style>	
.owl-carousel{position:relative;}
.slide_image{
	width: 100%;
    height: 400px;
}
.card{
	width: 95%;
    margin: 0 3%;
    box-shadow: 1px 2px 8px 1px #25232359;
    border: none;
    outline: none;
}
.card-1{
	position: absolute;
	top: 380px;
	z-index: 99;
}
.card-2{
	margin: 340px 2% 20px 3%;
    cursor: pointer;
}


@media (min-width: 724px) and (max-width: 750px) {
	.card-2{
		margin: 295px 2% 20px 3%;
		cursor: pointer;
	}
}
@media (min-width: 751px) and (max-width: 770px) {
	.card-2{
		margin: 300px 2% 20px 3%;
		cursor: pointer;
	}
}

@media (min-width: 386px) and (max-width: 723px) {
	.card-2{
		margin: 320px 2% 20px 3%;
		cursor: pointer;
	}
}
@media (min-width: 335px) and (max-width: 385px) {
	.card-2{
		margin: 260px 2% 20px 3%;
		cursor: pointer;
	}
	
	h5{
		font-size: 15px!important;
	}
	.complete_btn {
		margin-left: 10px!important;
		background: #069706;
		border-radius: 25px;
		color: white;
		padding: 0px 5px!important;
		cursor: pointer;
		font-size: 12px!important;
	}
	.card-text{
		font-size: 14px!important;
	}
	.card-title {
		font-size: 16px;
	}
	.card-text-heading {
		font-size: 14px!important;
	}
}
@media (min-width: 336px) and (max-width: 379px) {
	.card-2{
		margin: 260px 2% 20px 3%;
		cursor: pointer;
	}
	h5{
		font-size: 15px!important;
	}
	.complete_btn {
		margin-left: 10px!important;
		background: #069706;
		border-radius: 25px;
		color: white;
		padding: 0px 5px!important;
		cursor: pointer;
		font-size: 12px!important;
	}
	.card-text{
		font-size: 14px!important;
	}
	.card-title {
		font-size: 16px;
	}
	.card-text-heading {
		font-size: 14px!important;
	}
}
@media (min-width: 225px) and (max-width: 335px) {
	.card-2{
		margin: 280px 2% 20px 3%;
		cursor: pointer;
	}
	h5{
		font-size: 15px!important;
	}
	.complete_btn {
		margin-left: 10px!important;
		background: #069706;
		border-radius: 25px;
		color: white;
		padding: 0px 5px!important;
		cursor: pointer;
		font-size: 12px!important;
	}
	.card-text{
		font-size: 14px!important;
	}
	.card-title {
		font-size: 16px;
	}
	.card-text-heading {
		font-size: 14px!important;
	}
	
}
.card-4{
	margin: 5px 2% 20px 3%;
	cursor: pointer;
}
.card-title{
	font-size: 20px;
    font-weight: 600;
}
.card-text-heading{
	font-size: 18px;
    font-weight: 500;
}
.card-text{
	font-size: 17px;
    color: gray;
}
.inline_flex{
	display: inline-flex;
}
.complete_btn{
	margin-left: 20px;
    background: #069706;
    border-radius: 25px;
    color: white;
    padding: 1px 8px;
    cursor: pointer;
}
.fa-angle-right{
	font-size: 25px;
    float: right;
    color: gray;
	margin: 35px 0;
}
.card-3{
	margin: 5px 2% 20px 3%;
}
.card-text-1{
	font-size: 25px;
    font-weight: 600;
}
.card-text-2{
	font-size: 23px;
}
.card-text-3{
	font-size: 55px;
}
.card-text-4{
	font-size: 22px;
}
.card-text-5{
	font-size: 22px;
}
.col-md-4 .fa-sun{
	font-size: 95px;
}
.forcast{
	display: inline-block;
	margin: 0 55px;
}
.forcast .fa-sun,
.forcast .fa-cloud-sun,
.forcast .fa-cloud{
	font-size: 30px;
    margin: 15px 0px;
}
#privacyModal .modal-body{
	max-height: 350px;
    overflow: auto;
}

::-webkit-scrollbar {
    width: 3px;
}
::-webkit-scrollbar-thumb {
    background: #888;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
#privacyModal .modal-footer{
	border: none;
    justify-content: flex-start;
}
.privacy_check input{
	margin-right: 12px;
}
.privacy_check{
	width: 100%;
    font-size: 18px;
    font-weight: 500;
}
.privacy_check_btn{
	text-align: end;
    width: 100%;
}
.privacy_check_btn button:focus{
	box-shadow:none !important;
}
.privacy_check_btn button{
	font-size: 18px;
	font-weight: 500;
}
.disable{
	cursor: no-drop !important;
    color: gray;	
}
</style>

<div class="container" style="padding-top: 20px;margin: 0;width: 100%;max-width: 100% !important;">
	<h4>Casa Boutique Barcelona</h4>
	<div class="row">
		<div class="owl-carousel owl-theme">
			<div> <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTF8fGhvdGVsJTIwcm9vbXxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80" class="slide_image"> </div>
			<div> <img src="https://media.cntraveler.com/photos/56799015c2ebbef23e7d927b/master/pass/Hotelroom-Alamy.jpg" class="slide_image"> </div>
			<div> <img src="https://www.businessinsider.in/photo/68664363/heres-why-hotel-room-rates-in-india-may-double-in-the-next-3-to-4-years.jpg?imgsize=225157" class="slide_image"> </div>
			<div> <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTF8fGhvdGVsJTIwcm9vbXxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80" class="slide_image"> </div>
		</div>
		
		<div class="card card-1">
			<div class="card-body">
				<h5 class="card-title">Bienvenido, {{$data['booker']['first_name']}} {{$data['booker']['last_name']}}</h5>
				<p class="card-text">Aquí podrás ver información importante de tu reserva, hacer el check-in online y más.</p>
				<hr>
				<h5 class="card-title">Doble con baño</h5>
				<div class="row">
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Check in</div>
						<div class="card-text">{{$data['checkIn']}}</div>
					</div>
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Check out</div>
						<div class="card-text">{{$data['checkOut']}}</div>
					</div>
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Nights</div>
						<div class="card-text">{{$data['booking']['numberOfDays']}}</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Arrival Time</div>
						<div class="card-text">{{$data['booking']['time_start']}}</div>
					</div>             
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Price</div>
						<div class="card-text">{{$data['booking']['price']['calendar_price']['price']}} €</div>
					</div>
					<div class="col-md-4 col-4">
						<div class="card-text-heading">City tax</div>
						<div class="card-text">{{$data['booking']['price']['calendar_price']['tax']}} €</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-4 col-4">
						<div class="card-text-heading">VAT</div>
						<div class="card-text">{{$data['booking']['price']['calendar_price']['vat']}} €</div>
					</div>
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Total</div>
						<div class="card-text">{{$data['booking']['price']['calendar_price']['total']}} €</div>
					</div> 
					<div class="col-md-4 col-4">
						<div class="card-text-heading">Pending</div>
						<div class="card-text">{{$data['booking']['price']['calendar_price']['pending']}} €</div>
					</div>
				</div>         
			</div>
		</div>
		
		<div class="card card-2">

		<div class="row mt-3">
					<div class="col-md-12 col-12">
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
						Check daily prices
						</button>
						<!-- Modal -->
						<div class="modal fade" id="exampleModal" data-bs-backdrop="example" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Daily Prices</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
							@foreach ($data['dailyPrices'] as $dailyPrices)
							<p>{{ $dailyPrices['date'] }}: {{ $dailyPrices['value'] }} €</p>
							@endforeach
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							</div>
							</div>
						</div>
						</div>		
					</div>
				</div>
			<div class="card-body">
			<a style="text-decoration:none;color:black;" href='/web-check-in/{{$data["bookingCode"]}}/guests'>
				<div class="row">
					<div class="col-md-10 col-10">
						<h5 style="color:#444444;" class="card-title inline_flex">Check-in Online</h5><div class="complete_btn inline_flex"><span>Completado</span></div>
						<div class="card-text">Sube tus documentos, hazte un selfie, paga la reserva y entra sin colas el día de tu llegada.</div>
					</div>
					<div class="col-md-2 col-2">
						<i style="color:#007bff;" class="fas fa-angle-right"></i>
					</div>
				</div>
			</a>
			</div>
		</div>

		
		
		<div class="card card-3" >
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 col-12">
						<h5 class="card-title inline_flex">Check-In Instructions:</h5>
						<div class="card-text">
							<ul>
								<li>Specify your property’s check in and check out times.</li>
								<li>Enable guests to alert you to their check in time.</li>
								<li>See your check ins and check outs for the day.</li>
								<li>Allow guests to check in ahead of time.</li>
								<li>Process check out payments and print/email invoices.</li>
								<li>Send pre-check in and post-check out emails.</li>
							</ul> 
						</div>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="card card-3" >
			<div class="card-body" style="padding: 2px;">
				<div class="owl-carousel owl-theme">
					<div> <img src="https://www.thetechytraveller.com/wp-content/uploads/2018/06/2-8.jpg" class="slide_image_1"> </div>
					<div> <img src="https://media.istockphoto.com/photos/luxury-construction-hotel-with-swimming-pool-at-sunset-picture-id903417402?k=6&m=903417402&s=612x612&w=0&h=bAJc8pjScjsdstAz655e9Z6iMq8YvgUM5e_CW1O-9zw=" class="slide_image_1"></div>
					<div> <img src="https://cache.marriott.com/marriottassets/marriott/AUHXR/auhxr-ballroom-exterior-7362-hor-feat.jpg?downsize=1024px:*" class="slide_image_1"> </div>
					<div> <img src="https://cdn.kiwicollection.com/media/property/PR009702/l/009702-24-Main-Entrance.jpg?cb=1332806463" class="slide_image_1"> </div>
				</div>
			</div>
		</div>
		
		<div class="card card-3" >
			<div class="card-body" style="padding: 2px;">
				<img src="http://www.worldeasyguides.com/wp-content/uploads/2013/11/Plaza-Real-on-Map-of-Barcelona.jpg" class="hotel_map">
			</div>
		</div>
		
		<div class="card card-4">
			<div class="card-body">
			<a style="text-decoration:none;color:black;" href='/web-check-in/{{$data["bookingCode"]}}/terms-and-conditions'>

				<div class="row">
					<div class="col-md-10 col-10">
						<h5 style="color:#444444;" class="card-title">Horarios, condiciones y servicios</h5>
						<div class="card-text">Asegúrate de conocer los horarios de entrada y salida,</div>
					</div>
					<div class="col-md-2 col-2">
						<i style="color:#007bff;" class="fas fa-angle-right"></i>
					</div>
				</div>
			</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="border: none;">
				<h5 class="modal-title" id="exampleModalLabel">Política de datos</h5>
			</div>
			<div class="modal-body">
				Política De Privacidad Objetivo
				de la política de privacidad
				CHICSTAYS S.L. Informa a los
				usuarios que cumple con la
				normativa vigente sobre
				materia de protección de
				datos, y especialmente con el
				REGLAMENTO EUROPEO DE
				PROTECCIÓN DE DATOS.
				REGLAMENTO (UE) 2016/679
				DEL PARLAMENTO EUROPEO
				Y DEL CONSEJO de 25 de
				Mayo de 2016. Titular:
				CHICSTAYS S.L. a partir de
				ahora LA EMPRESA Direccion:
				Carrer Bailen 3, pal, 08010
				BARCELONA E-mail:
				info@chicstays.com Teléfono:
				685160394 C.I.F. B65121618
				Web:
			</div>
			<div class="modal-footer">
				<div class="privacy_check"><input type="checkbox"><span>He leido y acepto</span><div class="display_none error privacyError">Check the check box first.</div></div>
				<div class="privacy_check_btn"><button type="button" class="btn confirm_btn disable">ENTRAR</button></div>
			</div>
		</div>
	</div>
</div>

<script>
$('document').ready(function(){

	/*$('#privacyModal').modal({
		backdrop: 'static',
		keyboard: false
	})*/
	var mainslider = $(".owl-carousel");
	if (mainslider.length > 0) {
		mainslider.owlCarousel({
		   items: 1,
		   dots: true,
		   lazyLoad: true,
		   pagination: true,
		   autoPlay: 4000,
		   loop: true,
		   singleItem: true,
		   navigation: false,
		   stopOnHover: true,
		   //nav: true,
		   //navigationText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"]
		});
	}
	
	$('body').on('change','.privacy_check input',function(){
		//console.log($(this).is(":checked"));
		if($(this).is(":checked") == true){
			$(this).parent().parent().find('.btn').removeClass('disable');
		}else{
			$(this).parent().parent().find('.btn').addClass('disable');
		}
	});
	
	$('body').on('click','.confirm_btn',function(){
		//console.log($('.privacy_check input').is(":checked"));
		if($('.privacy_check input').is(":checked") == true){
			$('.privacyError').addClass('display_none');
			$('#privacyModal').modal('hide');
		}else{
			$('.privacyError').removeClass('display_none');
		}
	});
	
});
</script>




@endsection