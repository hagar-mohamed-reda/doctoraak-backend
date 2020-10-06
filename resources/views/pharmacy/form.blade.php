<div class="modal-body editModalPlace">
    <style>
        .form-group {
            height: 80px
        }
        label {
            color: black!important;
        }
    </style>
    <form method="post" class="form" action="http://localhost/doctoraak_v2/public/pharmacy/update/1" id="edit-form">
        <div class="box-body row">
            @csrf
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-name">{{ __('name') }}</label>
                <input required="" type="text" class="form-control " id="edit-name" name="name" value="{{ isset($pharmacy)? $pharmacy->name : '' }}" placeholder="{{ __('name') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-name_ar">{{ __('name_ar') }}</label>
                <input type="text" class="form-control " id="edit-name_ar" name="name_ar" value="{{ isset($pharmacy)? $pharmacy->name_ar : '' }}" placeholder="{{ __('name_ar') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-name_fr">{{ __('name_fr') }}</label>
                <input type="text" class="form-control " id="edit-name_fr" name="name_fr" value="{{ isset($pharmacy)? $pharmacy->name_fr : '' }}" placeholder="{{ __('name_fr') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-email">{{ __('email') }}</label>
                <input type="email" class="form-control " id="edit-email" name="email" value="{{ isset($pharmacy)? $pharmacy->email : '' }}" placeholder="{{ __('email') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-phone">{{ __('phone') }}</label>
                <input required="" type="text" class="form-control " id="edit-phone" name="phone" value="{{ isset($pharmacy)? $pharmacy->phone : '' }}" placeholder="{{ __('phone') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-password">{{ __('password') }}</label>
                <input required="" type="password" class="form-control " id="edit-password" name="password" value="{{ isset($pharmacy)? $pharmacy->password : '' }}" placeholder="{{ __('password') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-phone">{{ __('phone2') }}</label>
                <input type="text" class="form-control " id="edit-phone" name="phone2" value="{{ isset($pharmacy)? $pharmacy->phone2 : '' }}" placeholder="{{ __('phone2') }}">
            </div>
 
            <!--
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-address">{{ __('address') }}</label>
                <input required="" type="text" class="form-control " id="edit-address" name="address" value="{{ isset($pharmacy)? $pharmacy->address : '' }}" placeholder="{{ __('address') }}">
            </div>
            -->
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="cityId{{ isset($pharmacy)? $pharmacy->id : '' }}">{{ __('city') }}</label>
                <select required="" class="form-control" name="city_id" id="cityId{{ isset($pharmacy)? $pharmacy->id : '' }}"> 
                    @foreach(App\City::get() as $item)
                    <option value="{{ $item->id }}"  >{{ $item->name }}</option> 
                    @endforeach
                </select>
                <script>
                    @if (isset($pharmacy))
                    $("#cityId{{ $pharmacy->id }}").val('{{ $pharmacy->city_id }}');
                    @endif
                </script>
            </div>

            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="areaId{{ isset($pharmacy)? $pharmacy->id : '' }}">{{ __('area') }}</label>
                <select required="" class="form-control" name="area_id" id="areaId{{ isset($pharmacy)? $pharmacy->id : '' }}"> 
                    @foreach(App\Area::get() as $item)
                    <option value="{{ $item->id }}"  >{{ $item->name }}</option> 
                    @endforeach
                </select>
                <script>
                    @if (isset($pharmacy))
                    $("#cityId{{ $pharmacy->id }}").val('{{ $pharmacy->area_id }}');
                    @endif
                </script>
            </div>
  
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="pharmacyDoctorId{{ isset($pharmacy)? $pharmacy->id : '' }}">{{ __('pharmacy_doctor') }}</label>
                <select required="" class="form-control  " name="pharmacy_doctor_id" id="pharmacyDoctorId{{ isset($pharmacy)? $pharmacy->id : '' }}">
                    <option  >{{ __('select_pharmacy_doctor') }}</option>
                    @foreach(App\PharmacyDoctor::all() as $item)
                    <option value="" data-id="">all</option>
                    @endforeach
                </select>
                <script>
                    @if (isset($pharmacy))
                    $("#pharmacyDoctorId{{ isset($pharmacy)? $pharmacy->id : '' }}").val('{{ $pharmacy->pharmacy_doctor_id }}');
                    @endif
                </script>
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">

                <label for="map">{{ __('location') }}</label>
                <br>
                <input type="hidden" id="lat{{ isset($pharmacy)? $pharmacy->id : '' }}" name="lat" value="{{ isset($pharmacy)? $pharmacy->lat : '' }}">
                <input type="hidden" id="lng{{ isset($pharmacy)? $pharmacy->id : '' }}" name="lng" value="{{ isset($pharmacy)? $pharmacy->lng : '' }}">
                
                <div class="form-control cursor" onclick="$('.map-modal').show()">
                    <span class="fa fa-map-marker w3-large"></span>
                    <span> {{ __('location') }} </span> 
                </div>
                <p class="w3-tiny w3-text-gray" id="editLatLngPlacelocation{{ isset($pharmacy)? $pharmacy->id : '' }}"></p>
                <br>

                <div class="modal map-modal{{ isset($pharmacy)? $pharmacy->id : '' }}" style="z-index: 999999999999999">
                    <div class="w3-modal- modal-content w3-animate-top">
                        <div class="modal-header">
                            <center>{{ __('choose the location from the map') }}</center>
                        </div>
                        <div class="modal-body">
                            <div id="map{{ isset($pharmacy)? $pharmacy->id : '' }}" class="w3-block" style="height: 300px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div style="overflow: hidden;"></div><div class="gm-style" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;"><div tabindex="0" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: none;"><div style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"><div style="position: absolute; z-index: 988; transform: matrix(1, 0, 0, 1, -109, -79);"><div style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px;"><div style="width: 256px; height: 256px;"></div></div></div></div></div><div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"></div></div><div class="gm-style-pbc" style="z-index: 2; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;"><p class="gm-style-pbt"></p></div><div style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;"><div style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div></div></div></div><iframe aria-hidden="true" frameborder="0" tabindex="-1" style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe></div></div></div>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button type="button" class="btn btn-primary shadow" onclick="$('.map-modal{{ isset($pharmacy)? $pharmacy->id : '' }}').hide()">{{ __('ok') }}</button>
                                <button type="button" class="btn btn-primary shadow" onclick="getLocation()">{{ __('current location') }}</button>
                            </center>
                        </div>
                    </div>
                </div>

                <script>
                    function initMap{{ isset($pharmacy)? $pharmacy->id : '' }}() {
                        map = new google.maps.Map(document.getElementById('map{{ isset($pharmacy)? $pharmacy->id : '' }}'), {
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
                            document.getElementById("lng{{ isset($pharmacy)? $pharmacy->id : '' }}").value = position.lng();
                            document.getElementById("lat{{ isset($pharmacy)? $pharmacy->id : '' }}").value = position.lat();

                            map.panTo(position);
                            //
                            $('#editLatLngPlacelocation{{ isset($pharmacy)? $pharmacy->id : '' }}').html(position.lat() + "-" + position.lat());
                        }
                    }
                </script>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&amp;callback=initMap{{ isset($pharmacy)? $pharmacy->id : '' }}" async="" defer=""></script>

            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-">{{ __('photo') }} *</label>
                <div class="form-control cursor" onclick="$('.edit-image-photo').click()">
                    <span class="fa fa-image w3-large"></span>
                    <span> 90×90 </span>
                </div>
                <img class="imageView w3-round" src="{{ isset($pharmacy)? $pharmacy->url : '' }}" width="30px" height="30px" onclick="viewImage(this)" style="cursor: pointer">
                <input type="file" onchange="loadImage(this, event)" class="hidden edit-image-photo " name="photo" accept="image/x-png,image/gif,image/jpeg">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                <label for="edit-avaliable_days">{{ __('available_days') }}</label>
                <input required="" type="number" class="form-control " id="edit-avaliable_days" name="avaliable_days" value="{{ isset($pharmacy)? $pharmacy->avaliable_days : '' }}" placeholder="{{ __('available_days') }}">
            </div>
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class=""> 
                    <label for="editInputActive{{ isset($pharmacy)? $pharmacy->id : '' }}">{{ __('active') }} *</label> 
                    <input type="hidden" name="active" id="editInputActive{{ isset($pharmacy)? $pharmacy->id : '' }}" value="{{ isset($pharmacy)? $pharmacy->active : '' }}">
                    
                    <input type="checkbox" 
                           class="shadow" 
                           id="editActive{{ isset($pharmacy)? $pharmacy->id : '' }}" 
                           placeholder="active" 
                           @if (isset($pharmacy))
                           @if ($pharmacy->active == 1)
                           checked="" 
                           @endif
                           @endif
                            onclick="this.checked ? $('#editActive{{ isset($pharmacy)? $pharmacy->id : '' }}').val(1) : $('#editActive{{ isset($pharmacy)? $pharmacy->id : '' }}').val(0)">
                    <label for="edit-active" class="switch "></label>
                </div> 
            </div> 
 
            <div class="form-group w3-padding col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class=""> 
                    <label for="editInputDelivery{{ isset($pharmacy)? $pharmacy->id : '' }}">{{ __('active') }} *</label> 
                    <input type="hidden" name="active" id="editInputAeditInputDeliveryctive{{ isset($pharmacy)? $pharmacy->id : '' }}" value="{{ isset($pharmacy)? $pharmacy->active : '' }}">
                    
                    <input type="checkbox" 
                           class="shadow" 
                           id="editActive{{ isset($pharmacy)? $pharmacy->id : '' }}" 
                           placeholder="active" 
                           @if (isset($pharmacy))
                           @if ($pharmacy->active == 1)
                           checked="" 
                           @endif
                           @endif
                            onclick="this.checked ? $('#editActive{{ isset($pharmacy)? $pharmacy->id : '' }}').val(1) : $('#editActive{{ isset($pharmacy)? $pharmacy->id : '' }}').val(0)">
                    <label for="edit-active" class="switch "></label>
                </div> 
            </div>



        </div>
        <br>
        <br>
        <div class="box-footer">
            <button type="button" class="btn bg-gray btn-flat margin shadow" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn bg-purple btn-flat margin shadow">حفظ</button>
        </div>
    </form>

    <script>
        $(document).ready();
        if (init != undefined)
            init();

    </script>
</div>