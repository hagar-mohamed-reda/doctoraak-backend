<div class="modal-body">
                <style>
    .form-group {
        height: 80px
    }
    
    label {
        color: black!important;
    }
    
    .form {
        direction: rtl;
    }
</style>
<form method="post" class="form" action="http://localhost/doctoraak_v2/public/clinic/store" id="form">   
    <div class="box-body row">
        @csrf
       
        <div class="input-append date form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="fees">{{ __('fees') }}</label>
            <input required="" type="number" class="form-control " id="fees" name="fees" value="" placeholder="{{ __('fees') }}">
        </div> 
       
        <div class="input-append date form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="fees2">fees2</label>
            <input required="" type="number" class="form-control " id="fees2" name="fees2" value="" placeholder="fees2">
        </div> 
        
        <div class="input-append date form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="waiting_time">waiting_time</label>
            <input required="" type="number" class="form-control " id="waiting_time" name="waiting_time" value="" placeholder="waiting_time">
        </div> 
         
                <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">

            <label for="map">location</label>  
            <br>
            <input type="hidden" id="lat" name="lat">
            <input type="hidden" id="lng" name="lng">
            <div class="form-control cursor" onclick="$('.map-modal').show()">
                <span class="fa fa-map-marker w3-large"></span>
                <span> location </span>
            </div>
            <br>

            <div class="modal map-modal" style="z-index: 999999999999999">
                <div class="w3-modal- modal-content w3-animate-top">
                    <div class="modal-header">
                        <center>choose the location from the map</center>
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
                        map.panTo(position);
                    }

                }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&amp;callback=initMap" async="" defer=""></script>

        </div>
         
        <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="doctor_id">{{ __('doctor') }}</label>
            <select required="" class="form-control  " name="doctor_id" id="doctor_id">
                <option disabled="">{{ __('select doctor') }}</option>
                <option value="" data-id="">all</option>
                @foreach(App\Doctor::all() as $item)
                <option value="{{ $tem->id }}" data-id="">{{ $item->name }}</option> 
                @endforeach
            </select> 
        </div>
        

        
        
        
        <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="city_id">city</label>
            <select required="" class="form-control  " name="city_id" id="city_id">
                <option value="" data-id="">all</option>
                @foreach(App\City::all() as $item)
                <option value="{{ $item->id }}" data-id="">{{ $item->name_ar }}</option> 
                @endforeach
            </select> 
        </div>
        

        
        
        
                <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="area_id">area</label>
            <select required="" class="form-control  " name="area_id" id="area_id">
                <option disabled="" style="display: none;">select area</option>
                <option value="" data-id="" style="display: none;">all</option>
                @foreach(App\Area::all() as $item)
                <option value="{{ $item->id }}" data-id="{{ $item->city_id }}">{{ $item->name_ar }}</option> 
                @endforeach
            </select> 
        </div>
        

        
        
        
         
        <div class="input-append date form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="phone">phone</label>
            <input required="" type="text" class="form-control " id="phone" name="phone" value="" placeholder="phone">
        </div> 
        

        
        
        
                <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="">photo *</label>
            <div class="form-control cursor" onclick="$('.image-photo').click()">
                <span class="fa fa-image w3-large"></span>
                <span> 90×90 </span>
            </div> 
            <img class="imageView w3-round" width="30px" height="30px" onclick="viewImage(this)" style="cursor: pointer">
            <input type="file" onchange="loadImage(this, event)" class="hidden image-photo " name="photo" accept="image/x-png,image/gif,image/jpeg">
        </div>  
        

        
        
        
         
        <div class="input-append date form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
            <label for="available_days">available_days</label>
            <input required="" type="number" class="form-control " id="available_days" name="available_days" value="" placeholder="available_days">
        </div> 
        

        
        
        
                <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class=""> 
                <label for="">active *</label> 
                <input type="hidden" name="active" id="input-active" value="1">
                <input type="checkbox" class="shadow" id="active" placeholder="active" onchange="this.checked? $('#input-active').val(1) : $('#input-active').val(0)" checked="">
                <label for="active" class="switch ">active</label>
            </div> 
        </div>  
        

        
        
         <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class=""> 
                <label for="">availability *</label> 
                <input type="hidden" name="availability" id="input-availability" value="1">
                <input type="checkbox" class="shadow" id="availability" placeholder="availability" onchange="this.checked? $('#input-availability').val(1) : $('#input-availability').val(0)" checked="">
                <label for="availability" class="switch ">availability</label>
            </div> 
        </div>  
        

        
            </div>
    <br>
    <br>
    <div class="box-footer">
        <button type="button" class="btn btn-default btn-flat margin" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary btn-flat margin" onclick="$(this).parent().parent().find('input[type=file]').attr('required')? error('upload file') : ''">حفظ</button>
    </div>  
</form> 

<script>
//Date picker
    /*$('input[type=datetime]').attr("data-date-format", "yyyy-mm-dd hh:ii:ss");
    $('input[type=datetime]').datetimepicker({
      autoclose: true
    })*/
</script>
            </div>