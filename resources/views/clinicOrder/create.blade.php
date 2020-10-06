@extends("layout.app", ["title" => "العيادات"])


@php
$builder = (new App\Clinic())->getViewBuilder();
@endphp

@section("styles")
<style>
    .step {
        display: none
    }

    .step-1 {
        display: block
    }
    
    .order-search-item {
        display: none
    }
    
    .order-search-item1 {
        display: block
    }
    
    .another_person_data {
        display: none;
    }
</style>

@endsection

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li><a href="#" onclick="showPage('clinicorder')" >{{ __('reservations') }}</a></li> 
        <li class="active">{{ __('create reservation') }}</li>
    </ol>
</div>


@endsection


@section("content")
<div class="steps" id="order" >

    <div class="step step-1" >
        <div class="w3-block w3-round w3-padding" style="direction: ltr;height: 100px;background-image: url({{ url('/image/clinicheader.png') }});background-size: auto 100%" > 
            <br>
            <div class="w3-padding  w3-xlarge w3-text-white w3-center" >
                {{ __('select specialization') }}
            </div>
        </div> 
        <br>
        <div class="box-body" >
            <div class="w3-row" >

                @foreach(App\Specialization::orderBy('name')->get() as $item)
                <div class="col-lg-2 col-md-3 col-sm-4"
                     onclick="selectSp('{{ $item->id }}', this)"
                     style="padding: 5px" >
                    <div
                        class="w3-white shadow w3-round w3-center w3-hover-light-grey cursor w3-display-container" >
                        <div class="w3-doctoraak w3-padding" >
                            <img src="{{ $item->url }}" style="width: 70%!important" class="" >  
                        </div>
                        <br>
                        <div style="padding: 2px" class="w3-tiny" >
                            {{ $item->name }}
                        </div>

                        <div class="w3-display-topleft w3-padding" >
                            <input class="w3-check" type="radio" name="specialization_id" value="{{ $item->id }}"  >
                        </div>
                    </div>
                </div>
                @endforeach


            </div>
            <br>
            <div class="w3-padding w3-center" >
                <button class="fa fa-angle-right w3-xlarge w3-dark-doctoraak btn " onclick="step(2)"  ></button>
                <button class="fa fa-angle-left w3-xlarge w3-dark-doctoraak btn "></button>
            </div>
        </div>


    </div>


    <div class="step step-2" >
        <div class="w3-block w3-round w3-padding" style="direction: ltr;height: 100px;background-image: url({{ url('/image/clinicheader.png') }});background-size: auto 100%" > 
            <div class="w3-padding  w3-xlarge w3-text-white w3-center" >
                <b>{{ __('search') }}</b>
                <br>
                {{ __('select location') }}
            </div>
        </div> 
        <br>
        <div class="box-body" >
            <center>
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-default" onclick="$('.order-search-item').hide();$('.order-search-item2').slideDown(400)" >{{ __('map location') }}</button>
                    <button type="button" class="btn btn-default" onclick="$('.order-search-item').hide();$('.order-search-item1').slideDown(400)" >{{ __('city&area') }}</button> 
              </div>
            </center>
            <ul class="w3-ul" > 
                <li class="order-search-item order-search-item1" >
                    <div class="form-group w3-padding" > 
                        <label>{{ __('city') }}</label>
                        <select class="form-control select2 w3-block"  onchange="order.order.city_id = this.value"  > 
                            <option value="" >{{ __('all') }}</option>
                            @foreach(App\City::all() as $item)
                            <option value="{{ $item->id }}" >{{ $item->name_ar }}</option>
                            @endforeach
                        </select>
                    </div> 
                </li>
                <li class="order-search-item order-search-item1" > 
                    <div class="form-group w3-padding" > 
                        <label>{{ __('area') }}</label>
                        <select class="form-control" v-model="order.area_id"  > 
                            @foreach(App\Area::all() as $item)
                            <option value="{{ $item->id }}" v-if="order.city_id=='{{ $item->city_id }}'" >{{ $item->name_ar }}</option>
                            @endforeach
                        </select>
                    </div> 
                </li>
                <li class="order-search-item order-search-item2" > 
                    <div class="form-group w3-padding">

                        <label for="map">{{ __('location') }}</label>  
                        <br>
                        <input type="hidden" id="lat" name="lat">
                        <input type="hidden" id="lng" name="lng">
                        <div class="form-control cursor" onclick="$('.map-modal').show()">
                            <span class="fa fa-map-marker w3-large"></span>
                            <span> {{ __('location') }} </span>
                        </div>
                        <p class="w3-tiny w3-text-gray" id="latLngPlacelocation"></p>
                        <br>

                        <div class="modal map-modal" style="z-index: 999999999999999">
                            <div class="modal-dialog" >
                                <div class="w3-modal- modal-content w3-animate-top">
                                <div class="modal-header">
                                    <center>{{ __('choose the location from the map') }}</center>
                                </div>
                                <div class="modal-body">
                                    <div id="map" class="w3-block" style="height: 300px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div style="overflow: hidden;"></div><div class="gm-style" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;"><div tabindex="0" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: none;"><div style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"><div style="position: absolute; z-index: 988; transform: matrix(1, 0, 0, 1, -109, -79);"><div style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px;"><div style="width: 256px; height: 256px;"></div></div></div></div></div><div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"></div></div><div class="gm-style-pbc" style="z-index: 2; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;"><p class="gm-style-pbt"></p></div><div style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;"><div style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div></div></div></div><iframe aria-hidden="true" frameborder="0" tabindex="-1" style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe></div></div></div>
                                </div>
                                <div class="modal-footer">
                                    <center>
                                        <button type="button" class="btn btn-primary shadow" onclick="$('.map-modal').hide()">ok</button>
                                        <button type="button" class="btn btn-primary shadow" onclick="getLocation()">current location</button>
                                    </center>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                </li>
                @if (optional(App\Patient::find(request()->patient_id))->insurance_code_id)
                <li> 
                    <div class="form-group w3-display-container w3-padding"> 
                        <label for="">{{ __('insurance company') }}</label> 
                        <div class="w3-left" > 
                            <input type="checkbox" class="shadow" id="active" placeholder="active" onchange="this.checked ? order.order.insurance = 1 : order.order.insurance = 0"  >
                            <label for="active" class="switch "></label>
                        </div>
                    </div> 
                </li>
                @endif
            </ul>
        </div>
            <br>
            <div class="w3-padding w3-center" >
                <button class="fa fa-angle-right w3-xlarge w3-dark-doctoraak btn " onclick="step(3)"  ></button>
                <button class="fa fa-angle-left w3-xlarge w3-dark-doctoraak btn " onclick="step(1)"  ></button>
            </div>
    </div>

    
    <div class="step step-3" >
        <div class="w3-block w3-round w3-padding" style="direction: ltr;height: 100px;background-image: url({{ url('/image/clinicheader.png') }});background-size: auto 100%" > 
            <div class="w3-padding  w3-xlarge w3-text-white w3-center" >
                <b>{{ __('result found') }}</b>
                <br>
            </div>
        </div> 
        <br>
        <div class="box-body" >
            <ul class="w3-ul" >
                <li class="w3-padding w3-center" v-if="clinic_search" >
                    <i class="fa fa-spinner fa-spin w3-large w3-text-dark-doctoraak" ></i>
                </li>
                <li v-for='item in rows' >
                    <div class="media">
                    <div class="media-left">
                      <a href="#" v-if='item.doctor' >
                          <img class="media-object w3-circle" style="height: 60px;width: 60px" v-bind:src='item.doctor.photo' >
                      </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading" v-if='item.doctor'  >
                            <b v-html="item.doctor.name" ></b>
                        </h4>
                        <ul  >
                            <li v-if='item.city && item.area' >
                                <span class="w3-text-dark-doctoraak fa fa-map-marker" style="margin: 4px" ></span>
                                <b v-html="item.city.name + '-' + item.area.name" ></b>
                            </li>
                            <li v-if='item.fees' >
                                <span class="w3-text-dark-doctoraak fa fa-money" style="margin: 4px" ></span>
                                <b>{{ __('reservation') }} : </b><b v-html="item.fees" ></b>
                            </li>
                            <li v-if='item.fees2' >
                                <span class="w3-text-dark-doctoraak fa fa-money" style="margin: 4px" ></span>
                                <b>{{ __('consultation') }} : </b><b v-html="item.fees2" ></b>
                            </li>
                            <li>
                                <button class="btn w3-dark-doctoraak"
                                        data-toggle="modal"
                                        v-bind:data-doctor='item.doctor_id'
                                        v-bind:data-clinic='item.id'
                                        onclick="selectDoctor(this)"
                                        data-target="#reservationModal" >{{ __('book') }}</button>
                            </li>
                        </ul>
                    </div>
                  </div>
                </li>
            </ul>
        </div>
        <br>
        <div class="w3-padding w3-center" >
            <button class="fa fa-angle-left w3-xlarge w3-dark-doctoraak btn " onclick="step(2)"  ></button>
        </div>
    </div>
    
    
    <div class="step step-4" >
        <div class="w3-block w3-round w3-padding" style="direction: ltr;height: 100px;background-image: url({{ url('/image/clinicheader.png') }});background-size: auto 100%" > 
            <div class="w3-padding  w3-xlarge w3-text-white w3-center" >
                <b>{{ __('confirm message') }}</b>
                <br>
            </div>
        </div> 
        <br>
        <div class="box-body" >
            <div class="confirm_message text-center w3-large w3-text-doctoraak" ></div>
            <br>
            <center>
                <button class="btn btn-default" onclick="showPage('patient')" >{{ __('back') }}</button>
                <button class="btn w3-dark-doctoraak" onclick="showPage('clinicorder/create?api_token={{ request()->api_token }}&patient_id={{ request()->patient_id }}')" >{{ __('make another reservation') }}</button>
            </center>
        </div>
    </div>
</div>

@endsection

@section('section')
<!-- add modal -->
<div class="w3-modal fade"   role="dialog" id="reservationModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('add order') }}</center>
            </div>
            <div class="modal-body">
                
                <input type="hidden" class="form-control doctor_id"   >
                <input type="hidden" class="form-control clinic_id"   >
                 
                
                <div class="form-group" >
                    <label>{{ __('date') }}</label>
                    <input type="date" class="form-control order_date" required="" name="date" >
                </div>

                <div class="form-group" >
                    <label>{{ __('type') }}</label>
                    <select class="form-control order_type" required="" name="type" >
                        <option value="1" >{{ __('reservation') }}</option>
                        <option value="2" >{{ __('consultation') }}</option>
                        <option value="3" >{{ __('continue') }}</option>
                    </select>
                </div>

                <div class="form-group" >
                    <label>{{ __('shift') }}</label>
                    <select class="form-control order_part" required="" name="part" >
                        <option value="1" >{{ __('shift 1') }}</option>
                        <option value="2" >{{ __('shift 2') }}</option> 
                    </select>
                </div>

                <div class="form-group" > 
                    <input type="checkbox" class="order_another_person" onclick=""   >
                </div>
                
                <div class="form-group w3-display-container w3-padding"> 
                    <label>
                        <i class="fa fa-angle-left" style="padding: 5px" ></i>
                        {{ __('for another person') }}
                    </label> 
                    <input type="checkbox" class="shadow" id="anotherPersonDate"  placeholder="active" onchange="this.checked ? $('.another_person_data').slideDown(400) :  $('.another_person_data').slideUp(400)"  >
                    <label for="anotherPersonDate" class="switch "></label> 
                </div> 
                
                <div class="form-group another_person_data" >
                    <label>{{ __('full_name') }}</label>
                    <input type="text" class="form-control order_full_name"   >
                </div>
                
                <div class="form-group another_person_data" >
                    <label>{{ __('phone') }}</label>
                    <input type="text" class="form-control order_phone"  >
                </div> 
                
                <div class="form-group another_person_data" >
                    <label>{{ __('age') }}</label>
                    <input type="number" class="form-control order_age"  >
                </div>
                
                <div class="form-group another_person_data" >
                    <input type="hidden" class="form-control order_gender"  >
                    <label>{{ __('male') }}</label>
                    <input type="radio" onclick="$('.order_gender').val(this.value)" value="male" name="order_gender"  >
                    <label>{{ __('female') }}</label>
                    <input type="radio" onclick="$('.order_gender').val(this.value)" value="female" name="order_gender"  >
                </div>
                
                <div class="form-group" >
                    <button class="btn w3-doctoraak w3-block" onclick="makeOrder(this)" >{{ __('book now') }}</button>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("scripts")
<script>
    var table = null;
    
    function selectDoctor(div) {
        var doctor = $(div).attr('data-doctor');
        var clinic = $(div).attr('data-clinic');
        $('.doctor_id').val(doctor);
        $('.clinic_id').val(clinic);
    }
    
    function makeOrder(btn) {
        var html = $(btn).html();
        $(btn).attr('disabled', 'disabled');
        $(btn).html("<i class='fa fa-spin fa-spinner' ></i>");
        
        
        var notes = $('.order_full_name').val() + "," +
                    $('.order_phone').val() + "," +
                    $('.order_age').val()  + "," +
                    $('.order_gender').val();
        var order = {
            date: $('.order_date').val(),
            type: $('.order_type').val(),
            part: $('.order_part').val(),
            clinic_id: $('.clinic_id').val(),
            patient_id: '{{ request()->patient_id }}',
            notes: notes,
            api_token: '{{ request()->api_token }}'
        };
        
        if (!order.date || !order.type || !order.part || !order.patient_id) {
            return error('enter required data');
        }
        
        console.log(order);
        $.post("{{ url('/api/clinic/order/create') }}", $.param(order), function(response){
            
            if (response.status == 1)  {
                success(response.message);
                confirmMessage(response.message.replace(/,/g, '<br>'));
            }
            
            if (response.status == 0) 
                error(response.message.replace(',', '<br>'));
            
            
            $(btn).html(html);
            $(btn).removeAttr('disabled');
        });
    }
    
    function confirmMessage(message) {
        $('.confirm_message').html(message); 
        
        step(4);
    }

    function selectSp(sp_id, div) {
        order.order.specialization_id = sp_id;
        $(div).find('.w3-check')[0].checked = true;
        //
        step(2);
    }

    function step(n) {
        if (n == 2) {
            if (order.order.specialization_id == undefined)
                return error('{{ __("select specialization") }}');
        }
        
        if (n == 3) {
            loadClinics();
        }

        $('.step').slideUp(600);
        $('.step-' + n).slideDown(600);
    }
    
    function loadClinics() {
        order.clinic_search = true;
        $.get('{{ url("api/clinic/get") }}?'+$.param(order.order), function(response) {
            order.rows = response.data;
            order.clinic_search = false;
            
            
            success(response.message);
        });
    }

    var order = new Vue({
        el: '#order',
        data: {
            order: {
                city_id: null,
                area_id: null,
            },
            rows: [],
            clinic_search: false
        },
        methods: {
        },
        computed: {
        },
        watch: {
        }
    });

    $(document).ready(function () {

        $('.select2').select2();

    });


</script>
<script>
    var map;
    var marker = null;
    var placeMarker = null;
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(updatePosition);
        } else {
            //
        }
    }

    function updatePosition(position) {
        placeMarker(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), map);
        /*
         x.innerHTML = "Latitude: " + position.coords.latitude + 
         "<br>Longitude: " + position.coords.longitude;*/
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 30.0455965, lng: 31.2387195},
            zoom: 12
        });

        google.maps.event.addListener(map, 'click', function (e) {
            placeMarker(e.latLng, map);
        });

        placeMarker = function (position, map) {
            try {
                marker.setMap(null);
            } catch (e) {
            }
            marker = new google.maps.Marker({
                position: position,
                map: map
            });
            document.getElementById("lng").value = position.lng();
            document.getElementById("lat").value = position.lat();

            $(".lnglat").html(position.lat() + ", " + position.lng());
            
            order.order.lat = position.lat();
            order.order.lng = position.lng();
            map.panTo(position);
            //
            $('#latLngPlacelocation').html(position.lat() + "-" + position.lat());
        }

    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&amp;callback=initMap" async="" defer=""></script>

@endsection
