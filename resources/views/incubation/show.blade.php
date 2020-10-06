@extends("layout.app", ["title" => "المرضى"])

<style>
    .chip {
        display: inline-block;
        padding: 0 25px;
        height: 35px;
        font-size: 16px;
        line-height: 35px;
        border-radius: 25px;
        background-color: #f1f1f1;
    }

    .chip img {
        float: left;
        margin: 0 10px 0 -25px;
        height: 35px;
        width: 35px;
        border-radius: 50%;
    }

    .order-list-item {
        display: none;
    }

    .clinic-order-item {
        display: block;
    }

</style>

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li>
        <li><a href="#" onclick="showPage('incubation')" >{{ __('incubation') }}</a></li>
        <li class="active">{{ $incubation->name }}</li>
    </ol>
</div>
@endsection

@section("out")

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img 
                    class="profile-user-img img-responsive img-circle" 
                    onclick="viewImage(this)" 
                    width="100px" style="height: 100px" src="{{ url('/image/icon/incubator.png') }}" alt="incubation picture">

                <h3 class="profile-username text-center">{{ $incubation->name }}</h3>

                <p class="text-muted text-center">
                    @for($i = 0; $i < 5; $i ++)
                    @if ($i < $incubation->rate)
                    <i class="fa fa-star w3-text-orange" ></i>
                    @else 
                    <i class="fa fa-star w3-text-gray" ></i>
                    @endif
                    @endfor
                </p>

                <ul class="list-group list-group-unbordered">


                    <li class="list-group-item">
                        <b>{{ __('bed number') }}</b> <a class="pull-right">{{ number_format($incubation->bed_number) }}</a>
                    </li>

                </ul>

                <a href="#" class="btn btn-primary btn-block fa fa-print" onclick="window.print()"  > <b> {{ __('print') }} </b> </a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('details') }}</h3>
            </div>        
            <!-- /.box-header -->
            <div class="box-body"> 
                @if ($incubation->name_ar)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_ar') }}</strong> 
                <p class="text-muted">
                    {{ $incubation->name_ar }}
                </p>
                <hr>
                @endif

                @if ($incubation->name_fr)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_fr') }}</strong> 
                <p class="text-muted">
                    {{ $incubation->name_fr }}
                </p>
                <hr>
                @endif

                @if ($incubation->description)
                <strong><i class="fa fa-edit margin-r-5"></i> {{ __('description') }}</strong> 
                <p class="text-muted">
                    {{ $incubation->description }}
                </p>
                <hr>
                @endif

                @if ($incubation->description_ar)
                <strong><i class="fa fa-edit margin-r-5"></i> {{ __('description_ar') }}</strong> 
                <p class="text-muted">
                    {{ $incubation->description_ar }}
                </p>
                <hr>
                @endif

                @if ($incubation->description_en)
                <strong><i class="fa fa-edit margin-r-5"></i> {{ __('description_en') }}</strong> 
                <p class="text-muted">
                    {{ $incubation->description_en }}
                </p>
                <hr>
                @endif

                @if ($incubation->city_id)
                <strong><i class="fa fa-building margin-r-5"></i> {{ __('city') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($incubation->city)->name_ar }}" >{{ optional($incubation->city)->name }}</a>
                </p>
                <hr>
                @endif

                @if ($incubation->area_id)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('area') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($incubation->area)->name_ar }}+{{ optional($incubation->city)->name_ar }}" >{{ optional($incubation->area)->name }}</a>
                </p>
                <hr>
                @endif


                @if ($incubation->phone)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $incubation->phone }}" >{{ $incubation->phone }}</a>
                </p>
                <hr>
                @endif




            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#mapTab" data-toggle="tab">{{ __('location on map') }}</a></li> 
            </ul>
            <div class="tab-content">

                <div class="active tab-pane" id="mapTab">
                    <div id="map" class="w3-block" style="height:400px" ></div>
                </div> 


            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>

@endsection


@section("scripts")
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
            center: {lat: {{ $incubation->lat }}, lng: {{ $incubation->lng }} },
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
        
        
        placeMarker(new google.maps.LatLng({{ $incubation->lat }}, {{ $incubation->lng }}), map);

    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&callback=initMap"
async defer></script>
@endsection
