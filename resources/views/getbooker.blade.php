@extends('layouts.app')

@section('content')
<style>
    .information_title{
        padding: 15px 0px 0px;
        box-shadow: 0px 0px 8px 2px #00000042;
    }
    h4{
        display: inline-block;
    }
    .main_body{
    padding: 30px 3%;
    }
    .inner_body{
        padding: 50px 0px 15px;
    }
    .scanner_image{
        width: 340px;
    }
    .font-weight{
        font-weight:500;
    }
    .fa-arrow-left {
        font-size: 22px;
        color: #007bff;
        display: inline-block;
        cursor: pointer;
        float: left;
        margin-left: 10px;
    }
    a {
        color: gray;
    }
    a:hover{
        color: black;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        border-bottom: 2px solid;
    }
    .nav-tabs .nav-link {
        margin-bottom: -1px;
        border: none;
    }
    .input{
        border: none;
        border-bottom: 1px solid #c4b7b7;
        padding: 5px 2px;
        font-size: 18px;
    }
    .date{
        border: 1px solid #c4b7b7;
        padding: 5px 10px;
        font-size: 18px;
        border-radius: 8px;
    }
    .date:focus,
    .input:focus{
        outline:none;
    }
    label{
        font-size: 18px;
    }
    .document_image{
        margin-bottom: 15px;
        max-width: 350px;
        max-height: 200px;
    }
    .selfie_image {
        width: 220px;
    }
    .max-height{
        max-height: 300px;
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
    .signature_main{
        /* padding: 0 5%; */
    }
    .signature_box{
        margin: 10px 0px;
        border: 1px solid;
        /* padding: 60px 15px; */
        text-align: center;
        font-size: 50px;
        font-family: ui-sans-serif;
        color: lightgray;
    }
    @media (min-width: 225px) and (max-width: 400px) {
        .nav-link{
            font-size: 12px!important;
            padding: 8px 10px!important;
        }
        h4{
            font-size: 18px!important;
        }
        h5{
            font-size: 16px!important;
        }
        .font-weight{
            font-size: 14px!important;
        }
        .signature_box {
            padding: 35px 15px!important;
            font-size: 30px!important;
        }
        label {
            font-size: 14px!important;
        }
        .input {
            font-size: 14px!important;
        }
        .date {
            padding: 3px 4px!important;
            font-size: 14px!important;
            width: 138px!important;
        }
        input{width: -webkit-fill-available!important;}
        .col-sm-6{padding: 0!important;}
        .fa-arrow-left {
            font-size: 18px!important;
        }
        .step_2{
            width:65%!important;
        }
    }
</style>

<div class="container" style="max-width: 100% !important;">
	<div class="row information_title">
		<div class="col-md-12 text-center">
        <a style="text-decoration:none;color:black;" href='/web-check-in/{{$data["bookingCode"]}}/guests'>
			<i class="fa fa-arrow-left back"></i>
        </a>
			<h4>Check-in Online</h4>
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<a style="color: #444444;" class="nav-item nav-link active" id="ESCANER-tab" data-toggle="tab" href="#ESCANER" role="tab" aria-controls="ESCANER" aria-selected="true">ESCANER</a>
					<a style="color: #444444;" class="nav-item nav-link" id="DATOS-tab" data-toggle="tab" href="#DATOS" role="tab" aria-controls="DATOS" aria-selected="false">DATOS</a>
					<a style="color: #444444;" class="nav-item nav-link" id="SELFIE-tab" data-toggle="tab" href="#SELFIE" role="tab" aria-controls="SELFIE" aria-selected="false">SELFIE</a>
					<a style="color: #444444;" class="nav-item nav-link" id="FIRMA-tab" data-toggle="tab" href="#FIRMA" role="tab" aria-controls="FIRMA" aria-selected="false">FIRMA</a>
				</div>
			</nav>
		</div>
	</div>
    <form action='/web-check-in/{{$data["bookingCode"]}}/add-booker-details' method="post" id="form" enctype="multipart/form-data">
    @csrf
        <div class="main_body">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="ESCANER" role="tabpanel" aria-labelledby="ESCANER-tab">
                    <!-- camera -->
                    <div class="inner_body text-center">
                        <div class="row">
                            <div class="col-sm mt-2">
                            <button type="button" class=" btn btn-secondary"  id="startDefaultAllButtonId">Start Camera</button>
                            </div>
                            <div class="col-sm mt-2">
                            <button type="button" class=" btn btn-success"  id="takePhotoButtonId">Take Photo</button>
                            </div>
                            <div class="col-sm mt-2">
                            <!-- <label style="" for="facingModeSelectId">facingMode</label> -->
                                <select class="btn btn-success" style="display:none;"id="facingModeSelectId" name="facingMode">
                                <option style="display:none;"value="ENVIRONMENT" selected="selected">Environment</option>
                                <option style="display:none;"value="USER">User</option>
                                </select>
                            <button type="button" style="display:none" class=" btn btn-warning"  id="stopCameraButtonId">Stop Camera</button>
                            </div>
                        </div>
                        <div class="row">
                            <div  id="divId">            

                                <button type="button" style="display: none;" id="startDefaultResolutionButtonId">startDefaultResolution</button>
                                <button type="button" style="display: none;" id="startMaxResolutionId">startMaxResolution</button>
                                <button type="button" style="display: none;" id="showInputVideoDeviceInfosButtonId">showInputVideoDeviceInfos</button>

                                <br/>
                                <div style="display: none;" id="cameraSettingsId"></div>
                                <div id="inputVideoDeviceInfosId"></div>
                                <div class="text-center">
                                <video class="showImage me-2" style=" display: none; width: 300px; height:300px;" id="videoId" autoplay="true" playsInline></video>
                                <!-- @if ($data['booker']['id_image'] == null)
                                @else
                                <img style="width: 300px; height:300px;" src="{{$data['booker']['id_image']}}">
                                @endif
                                <img style="display:none; width: 300px; height:300px;" class="float-start showImage me-2" id="imgId"> -->

                                </div> 
                                <!-- <input type="hidden" name="image" id="imageValue" value="{{$data['booker']['id_image']}}"> -->
                            </div>
                        </div>
                    </div>
                    <!-- end camera -->	
                    <div class="inner_body text-center">
                        @if ($data['booker']['id_image'] == null)
                        @else
                        <img src="{{$data['booker']['id_image']}}" class="img-fluid scanner_image" >
                        @endif
                    </div>
                    <div class="text-center mt-3 font-weight">
                        Haz una foto de tu documento para que podamos
                        leer los datos. Evita reflejos y altas exposiciones.
                    </div>
                    <h5 class="text-center">No te llevará más de un minuto.</h5>
                    <div class="text-center">
                        <button class="btn btn-info mt-5 step_1" style="width:54%" type="button">Empezar ></button>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="DATOS" role="tabpanel" aria-labelledby="DATOS-tab">
                    <div class="row form-group">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 text-center">
                        @if ($data['booker']['id_image'] == null)
                        @else
                        <img required id="datosImage" style="width: 300px; height:300px;" src="{{$data['booker']['id_image']}}">
                        @endif
                        <img required style="display:none; width: 300px; height:300px;" class="float-start showImage me-2 document_image" id="imgId">

                        </div> 
                        <input required type="hidden" name="image" id="imageValue" value="{{$data['booker']['id_image']}}">
                    
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Name</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="first_name" class=" input" value="{{$data['user']['first_name']}}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Surname</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="last_name" class=" input" value="{{$data['user']['last_name']}}">
                        </div>
                    </div>				
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Document Type</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required name="doc_type" class="input">
                                <option value="{{$data['booker']['identification']}}" selected>{{$data['booker']['identification']}}</option>
                                <option value="passport">Passport</option>
                                <option value="id">Id</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                    </div>  
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Document Number</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="doc_id" value="{{$data['booker']['identification_number']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Date of Issue</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="date" name="date_of_issue" value="{{$data['booker']['identification_date_of_issue']}}" class="date_expedition date" id="date_expedition">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Date of Expiry</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="date" name="date_of_expiry" value="{{$data['booker']['identification_date_of_expiry']}}" class="date_expiration date" id="date_expiration">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Sex</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required name="gender" class="input" id="gender">
                                <option value="{{$data['user']['gender']}}" selected>{{$data['user']['gender']}}</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="none">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Date of Birth</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required  type="date" name="date_of_birth" value="{{$data['user']['birth_date']}}" class="date_of_birth date" id="date_of_birth">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Nationality</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="nationality" value="{{$data['userCountry']['name']}}"class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Language</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required  type="text" name="language" value="{{$data['userLanguage']['value']}}" class=" input">
                        </div>
                    </div>

                    <hr>

                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Adult</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="number" name="adult" value="{{$data['booking']['adult_count']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Children</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="number" name="children" value="{{$data['booking']['children_count']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Arrival Time</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="arrival_time" value="{{$data['booking']['time_start']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Phone Number</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="tel" name="phone_number" value="{{$data['user']['phone_number']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Zip Code</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="zipcode" value="{{$data['user']['postal_code']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Email</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="email" name="email" value="{{$data['user']['email']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Country</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required id="country" name="countryId" class="input">
                                <option value="{{$data['userCountry']['id']}}" selected >{{$data['userCountry']['name']}}</option>
                                @foreach($data['countryList'] as $key => $country)
                                <option required value="{{$key}}"> {{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Province/State</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required name="stateId" id="state"class="input">
                                <option value="{{$data['userState']['id']}}" selected >{{$data['userState']['name']}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>City</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="city" name="city" value="{{$data['user']['city']}}" class=" input">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Address</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <input required type="text" name="street" value="{{$data['user']['street']}}" placeholder="street"  class=" input">
                            <input type="text" name="buidling_number" value="{{$data['user']['buidling_no']}}" placeholder="building name or number" class=" input">
                            <input type="number" name="floor" value="{{$data['user']['floor']}}" placeholder="floor" class=" input">
                        </div>
                    </div>				
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Source</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required name="source"  class="input">
                                <option value="{{$data['booking']['source']}}" selected>{{$data['booking']['source']}}</option>
                                <option value="business">Business</option>
                                <option value="google">Google</option>
                                <option value="other">Other</option>
                                <option value="direct">Direct</option>
                            </select>
                        </div>
                    </div>				
                    <div class="row form-group">
                        <div class="col-md-5 col-sm-5 col-5 text-right"><label>Segment</label></div>
                        <div class="col-md-1 col-sm-1 col-1"></div>
                        <div class="col-md-6 col-sm-6 col-5">
                            <select required name="segment" class="input">
                                <option value="{{$data['booking']['segment']}}" selected>{{$data['booking']['segment']}}</option>
                                <option value="family">Family</option>
                                <option value="work_trip">Work Trip</option>
                                <option value="party_trip">Party Trip</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-info mt-5 step_2" style="width:54%" type="button">TODO CORRECTO ></button>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="SELFIE" role="tabpanel" aria-labelledby="SELFIE-tab">
                    <div class="inner_body text-center">
                            <!-- camera -->
                        <div class="row">
                            <div class="col-sm mt-2">
                            <button type="button" class=" btn btn-secondary"  id="startDefaultAllButtonId01">Start Camera</button>
                            </div>
                            <div class="col-sm mt-2">
                            <button type="button" class=" btn btn-success"  id="takePhotoButtonId01">Take Selfie</button>
                            </div>
                            <div class="col-sm">
                            <button type="button" style="display:none" class=" btn btn-warning"  id="stopCameraButtonId01">Stop Camera</button>
                            </div>
                        </div>
                        <div class="row">
                            <div  id="divId">
                                <label style="display: none;" for="facingModeSelectId01">facingMode</label>
                                <select style="display: none;"id="facingModeSelectId01" name="facingMode01">
                                <option style="display: none;"value="ENVIRONMENT">environment</option>
                                <option style="display: none;"value="USER" selected="selected">user</option>
                                </select>

                                <button type="button" style="display: none;" id="startDefaultResolutionButtonId01">startDefaultResolution</button>
                                <button type="button" style="display: none;" id="startMaxResolutionId01">startMaxResolution</button>
                                <button type="button" style="display: none;" id="showInputVideoDeviceInfosButtonId01">showInputVideoDeviceInfos</button>

                                <br/>
                                <div style="display: none;" id="cameraSettingsId01"></div>
                                <div id="inputVideoDeviceInfosId01"></div>
                                <div class="text-center">
                                <video class="showImage  me-2" style="display:none; width: 300px; height:300px;" id="videoId01" autoplay="true" playsInline></video>
                                @if ($data['booker']['booker_selfie'] == null)
                                @else
                                <img required class="text-center" id="selfieImage" style="width: 300px; height:300px;" src="{{$data['booker']['booker_selfie']}}">
                                @endif
                                <img required style="display:none; width: 300px; height:300px;" class=" showImage me-2" id="imgId01">

                                </div> 
                                <input required type="hidden" name="booker_selfie" id="imageValue01" value="{{$data['booker']['booker_selfie']}}">
                            </div>
                        </div>
                        <!-- end camera -->
                        <!-- <img src="/assets/img/selfie.jpg" class="selfie_image" > -->
                    </div>
                    
                    <div class="text-center mt-3 font-weight">
                        Necesitamos verificar tu autenticidad, hazte un
                        selfie sonriendo.
                    </div>
                    <div class="text-center">
                        <button class="btn btn-info mt-5 step_3" style="width:54%" type="button">Validar ></button>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="FIRMA" role="tabpanel" aria-labelledby="FIRMA-tab">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <h5>Política de datos</h5>
                            <div class="mt-3 font-weight max-height">
                                QUE SUS DATOS ESTEN INSCRITOS EN UN FICHERO
                                Introducir datos en una o algunas de las hojas de
                                captación de datos implica la aceptación de estos
                                términos de uso y política de privacidad, dando a
                                entender que ha sido informado de las condiciones de
                                uso y aviso legal para el mismo y se compromete a su
                                entero cumplimiento durante la navegación y
                                participación en nuestra web. EVITAR LA TRANSMISIÓN
                                DE DATOS A TERCERAS EMPRESAS SIN
                                CONSENTIMIENTO EXPRESO DEL USUARIO Así mismo y
                            </div>
                            <hr>
                            <h5>Firmar para aceptar los términos.</h5>
                            <div class="signature_main text-center">                                
                                <span <?php if($data['booker']['booker_signature'] != null){ } else{ ?> style="display:none;" <?php }?> required class="text-center">Your Current Signature: </span><br>
                                <img <?php if($data['booker']['booker_signature'] != null){?> style="display:inline_block;width:300px;height:150px;border:1px solid silver;margin-bottom:3rem;" <?php } else{ ?> style="display:none;" <?php }?> required class="text-center" id="signImage" style="width: 300px; height:150px;" src="{{$data['booker']['booker_signature']}}"><br>
                                <span >Your New Signature:</span><br>
                                <canvas id="signature-pad" class="signature_box">
                                </canvas>
                                <div class="text-center">
                                    <button id="clear" class="btn btn-danger" style="width:100%" type="button">Limpiar</button>
                                    <img style="display:none; width: 300px; height:300px;" class="float-start showImage me-2" id="imgId02">
                                    <input type="hidden" name="booker_signature" id="imageValue02" value="{{$data['booker']['booker_signature']}}">
                                </div>
                            </div>
                            <div class="text-center">
                                <!-- <button id="proceed" class="btn btn-info mt-5 proceed_btn" style="width:100%" type="button">Submit</button> -->
                                <a id="proceed" style="color:white;width:100%"  class="btn btn-success btn-md mt-3 " tabindex="-1" role="button" aria-disabled="true">Make Payment</a>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>				
			    </div>
            </div>
        </div>
    </form>
</div>
<hr>

<!-- Configure a few settings and attach camera -->
<script language="JavaScript">

    

    $('body').on('click','.step_1',function(){
		$('#ESCANER-tab').removeClass('active');
		$('#ESCANER').removeClass('active').removeClass('show');
		$('#ESCANER-tab').attr('aria-selected',false);
		
		$('#DATOS-tab').addClass('active');
		$('#DATOS').addClass('active').addClass('show');
		$('#DATOS-tab').attr('aria-selected',true);
	});

    $('body').on('click','.step_2',function(){
		$('#DATOS-tab').removeClass('active');
		$('#DATOS').removeClass('active').removeClass('show');
		$('#DATOS-tab').attr('aria-selected',false);
		
		$('#SELFIE-tab').addClass('active');
		$('#SELFIE').addClass('active').addClass('show');
		$('#SELFIE-tab').attr('aria-selected',true);
	});
	
	$('body').on('click','.step_3',function(){
		$('#SELFIE-tab').removeClass('active');
		$('#SELFIE').removeClass('active').removeClass('show');
		$('#SELFIE-tab').attr('aria-selected',false);
		
		$('#FIRMA-tab').addClass('active');
		$('#FIRMA').addClass('active').addClass('show');
		$('#FIRMA-tab').attr('aria-selected',true);
	});

    var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)'
    });
    var proceed = document.getElementById('proceed');
    var cancelButton = document.getElementById('clear');
    var imgElement02 = document.getElementById('imgId02');
    var imageValue02 = document.getElementById('imageValue02');
    var signImage = document.getElementById('signImage');
    // console.log (signImage.src);

    proceed.addEventListener('click', function(event) {
        if(signImage.getAttribute('src') != "" ){ // src is available
            if(signaturePad.isEmpty()) {
                alert(signImage.getAttribute('src'));
                imgElement02.src = signImage.getAttribute('src');
                console.log(imgElement02.src);
                imageValue02.value = imgElement02.src;

                document.getElementById("form").submit(); 
                // proceed.href='/web-check-in/{{$data["bookingCode"]}}/payment';               
            }
            else {
                alert("new");
                var data = signaturePad.toDataURL();
                imgElement02.src = data;
                console.log(imgElement02.src);
                imageValue02.value = imgElement02.src;

                document.getElementById("form").submit();
                // proceed.href='/web-check-in/{{$data["bookingCode"]}}/payment';
            }
        } else {
            if(signaturePad.isEmpty()) {
                alert('Please enter a signature!');
            }
            else {
                var data = signaturePad.toDataURL();
                imgElement02.src = data;
                console.log(imgElement02.src);
                imageValue02.value = imgElement02.src;

                document.getElementById("form").submit();
                // proceed.href='/web-check-in/{{$data["bookingCode"]}}/payment';
                console.log(data);
            }
        }
    
    });

    cancelButton.addEventListener('click', function(event) {
        signaturePad.clear();
    });

    // camera
    var FACING_MODES = JslibHtml5CameraPhoto.FACING_MODES;
    var IMAGE_TYPES = JslibHtml5CameraPhoto.IMAGE_TYPES;

    // get video and image elements
    var videoElement = document.getElementById('videoId');
    var imgElement = document.getElementById('imgId');
    var datosImage = document.getElementById('datosImage');

    // get select and buttons elements
    var facingModeSelectElement = document.getElementById('facingModeSelectId');
    var startCameraDefaultAllButtonElement = document.getElementById('startDefaultAllButtonId');
    var startDefaultResolutionButtonElement = document.getElementById('startDefaultResolutionButtonId');
    var startMaxResolutionButtonElement = document.getElementById('startMaxResolutionId');
    var takePhotoButtonElement = document.getElementById('takePhotoButtonId');
    var stopCameraButtonElement = document.getElementById('stopCameraButtonId');
    var cameraSettingElement = document.getElementById('cameraSettingsId');
    var showInputVideoDeviceInfosButtonElement = document.getElementById('showInputVideoDeviceInfosButtonId');
    var inputVideoDeviceInfosElement = document.getElementById('inputVideoDeviceInfosId');
    var imageValue = document.getElementById('imageValue');
    var form = document.getElementById('form');


    $('#country').change(function(){
        var countryID = $(this).val();  
        if(countryID){
            $.ajax({
                type:"GET",
                url:"{{url('getState')}}?country_id="+countryID,
                success:function(res){        
                    if(res){
                        $("#state").empty();
                        $("#state").append('<option></option>');
                        $.each(res,function(key,value){
                        $("#state").append(`<option required value="`+key+`">`+value+`</option>`);
                        });                
                    }else{
                        $("#state").empty();
                    }
                }
            });
            }else{
                $("#state").empty();
            }   
    });


    // instantiate JslibHtml5CameraPhoto with the videoElement
    var cameraPhoto = new JslibHtml5CameraPhoto.default(videoElement);

    function startCameraDefaultAll () {
        imgElement.style.display = 'none';
        videoElement.style.display = 'inline-block';
        cameraPhoto.startCamera()
        .then(() => {
            var log = `Camera started with default All`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    // start the camera with prefered environment facingMode ie. ()
    // if the environment facingMode is not avalible, it will fallback
    // to the default camera avalible.
    function startCameraDefaultResolution () {
        // var facingMode = facingModeSelectElement.value;
        var facingMode = 'ENVIRONMENT';
        cameraPhoto.startCamera(FACING_MODES[facingMode])
        .then(() => {
            var log =
                `Camera started with default resolution and ` +
                `prefered facingMode : ${facingMode}`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    // function called by the buttons.
    function takePhoto () {
        var sizeFactor = 1;
        var imageType = IMAGE_TYPES.JPG;
        var imageCompression = 1;

        var config = {
        sizeFactor,
        imageType,
        imageCompression
        };

        var dataUri = cameraPhoto.getDataUri(config);
        imgElement.src = dataUri;
        console.log(imgElement.src);
        imageValue.value = imgElement.src;
        console.log(imgElement.src.length);
        cameraPhoto.stopCamera();
        imgElement.style.display = 'inline-block';
        datosImage.style.display = 'none';
        videoElement.src = dataUri;
        videoElement.style.display = 'none';

    }

    function showCameraSettings () {
        var settings = cameraPhoto.getCameraSettings();

        // by default is no camera...
        var innerHTML = 'No camera';
        if (settings) {
        var {aspectRatio, frameRate, height, width} = settings;
        innerHTML = `
            aspectRatio:${aspectRatio}
            frameRate: ${frameRate}
            height: ${height}
            width: ${width}
        `;
        }
        cameraSettingElement.innerHTML = innerHTML;
    }

    function showInputVideoDeviceInfos () {
        var inputVideoDeviceInfos = cameraPhoto.getInputVideoDeviceInfos();

        // by default is no inputVideoDeviceInfo...
        var innerHTML = 'No inputVideoDeviceInfo';
        if (inputVideoDeviceInfos) {
        innerHTML = '';
        inputVideoDeviceInfos.forEach((inputVideoDeviceInfo) => {
            var {kind, label, deviceId} = inputVideoDeviceInfo;
            var inputVideoDeviceInfoHTML = `
                kind: ${kind}
                label: ${label}
                deviceId: ${deviceId}
                <br/>
            `;
            innerHTML += inputVideoDeviceInfoHTML;
        });
        }
        inputVideoDeviceInfosElement.innerHTML = innerHTML;
    }

    function stopCamera () {
        cameraPhoto.stopCamera()
        .then(() => {
            console.log('Camera stoped!');
        })
        .catch((error) => {
            console.log('No camera to stop!:', error);
        });
    }

    function startCameraMaxResolution () {
        // var facingMode = facingModeSelectElement.value;
        var facingMode = 'ENVIRONMENT';
        cameraPhoto.startCameraMaxResolution(FACING_MODES[facingMode])
        .then(() => {
            var log =
                `Camera started with maximum resoluton and ` +
                `prefered facingMode : ${facingMode}`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // update camera setting
        setInterval(() => {
        showCameraSettings();
        }, 500);

        // bind the buttons to the right functions.
        startCameraDefaultAllButtonElement.onclick = startCameraDefaultAll;
        startDefaultResolutionButtonElement.onclick = startCameraDefaultResolution;
        startMaxResolutionButtonElement.onclick = startCameraMaxResolution;
        takePhotoButtonElement.onclick = takePhoto;
        stopCameraButtonElement.onclick = stopCamera;
        showInputVideoDeviceInfosButtonElement.onclick = showInputVideoDeviceInfos;
    });

    // selfie
    var FACING_MODES01 = JslibHtml5CameraPhoto.FACING_MODES;
    var IMAGE_TYPES01 = JslibHtml5CameraPhoto.IMAGE_TYPES;

    // get video and image elements
    var videoElement01 = document.getElementById('videoId01');
    var imgElement01 = document.getElementById('imgId01');
    var selfieImage = document.getElementById('selfieImage');


    // get select and buttons elements
    var facingModeSelectElement01 = document.getElementById('facingModeSelectId01');
    var startCameraDefaultAllButtonElement01 = document.getElementById('startDefaultAllButtonId01');
    var startDefaultResolutionButtonElement01 = document.getElementById('startDefaultResolutionButtonId01');
    var startMaxResolutionButtonElement01 = document.getElementById('startMaxResolutionId01');
    var takePhotoButtonElement01 = document.getElementById('takePhotoButtonId01');
    var stopCameraButtonElement01 = document.getElementById('stopCameraButtonId01');
    var cameraSettingElement01 = document.getElementById('cameraSettingsId01');
    var showInputVideoDeviceInfosButtonElement01 = document.getElementById('showInputVideoDeviceInfosButtonId01');
    var inputVideoDeviceInfosElement01 = document.getElementById('inputVideoDeviceInfosId01');
    var imageValue01 = document.getElementById('imageValue01');
    var form01 = document.getElementById('form01');

    // instantiate JslibHtml5CameraPhoto with the videoElement
    var cameraPhoto01 = new JslibHtml5CameraPhoto.default(videoElement01);

    function startCameraDefaultAll01 () {
        imgElement01.style.display = 'none';
        videoElement01.style.display = 'inline-block';
        cameraPhoto01.startCamera()
        .then(() => {
            var log = `Camera started with default All`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    // start the camera with prefered environment facingMode ie. ()
    // if the environment facingMode is not avalible, it will fallback
    // to the default camera avalible.
    function startCameraDefaultResolution01 () {
        // var facingMode01 = facingModeSelectElement01.value;
        var facingMode01 = 'USER';
        cameraPhoto01.startCamera(FACING_MODES01[facingMode01])
        .then(() => {
            var log =
                `Camera started with default resolution and ` +
                `prefered facingMode : ${facingMode01}`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    // function called by the buttons.
    function takePhoto01 () {
        var sizeFactor = 1;
        var imageType = IMAGE_TYPES.JPG;
        var imageCompression = 1;

        var config = {
        sizeFactor,
        imageType,
        imageCompression
        };

        var dataUri = cameraPhoto01.getDataUri(config);
        imgElement01.src = dataUri;
        console.log(imgElement01.src);
        imageValue01.value = imgElement01.src;
        console.log(imgElement01.src.length);
        cameraPhoto01.stopCamera();
        imgElement01.style.display = 'inline-block';
        selfieImage.style.display = 'none';
        videoElement01.src = dataUri;
        videoElement01.style.display = 'none';

    }

    function showCameraSettings01 () {
        var settings = cameraPhoto01.getCameraSettings();

        // by default is no camera...
        var innerHTML = 'No camera';
        if (settings) {
        var {aspectRatio, frameRate, height, width} = settings;
        innerHTML = `
            aspectRatio:${aspectRatio}
            frameRate: ${frameRate}
            height: ${height}
            width: ${width}
        `;
        }
        cameraSettingElement01.innerHTML = innerHTML;
    }

    function showInputVideoDeviceInfos01 () {
        var inputVideoDeviceInfos = cameraPhoto01.getInputVideoDeviceInfos01();

        // by default is no inputVideoDeviceInfo...
        var innerHTML = 'No inputVideoDeviceInfo';
        if (inputVideoDeviceInfos) {
        innerHTML = '';
        inputVideoDeviceInfos.forEach((inputVideoDeviceInfo) => {
            var {kind, label, deviceId} = inputVideoDeviceInfo;
            var inputVideoDeviceInfoHTML = `
                kind: ${kind}
                label: ${label}
                deviceId: ${deviceId}
                <br/>
            `;
            innerHTML += inputVideoDeviceInfoHTML;
        });
        }
        inputVideoDeviceInfosElement01.innerHTML = innerHTML;
    }

    function stopCamera01 () {
        cameraPhoto01.stopCamera()
        .then(() => {
            console.log('Camera stoped!');
        })
        .catch((error) => {
            console.log('No camera to stop!:', error);
        });
    }

    function startCameraMaxResolution01 () {
        // var facingMode01 = facingModeSelectElement01.value;
        var facingMode01 = 'USER';
        cameraPhoto01.startCameraMaxResolution01(FACING_MODES01[facingMode01])
        .then(() => {
            var log =
                `Camera started with maximum resoluton and ` +
                `prefered facingMode : ${facingMode01}`;
            console.log(log);
        })
        .catch((error) => {
            console.error('Camera not started!', error);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // update camera setting
        setInterval(() => {
        showCameraSettings01();
        }, 500);

        // bind the buttons to the right functions.
        startCameraDefaultAllButtonElement01.onclick = startCameraDefaultAll01;
        startDefaultResolutionButtonElement01.onclick = startCameraDefaultResolution01;
        startMaxResolutionButtonElement01.onclick = startCameraMaxResolution01;
        takePhotoButtonElement01.onclick = takePhoto01;
        stopCameraButtonElement01.onclick = stopCamera01;
        showInputVideoDeviceInfosButtonElement01.onclick = showInputVideoDeviceInfos01;
    });

    



    
</script>

@endsection