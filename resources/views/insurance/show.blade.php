@extends("layout.app", ["title" => $insurance->name])

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
        <li><a href="#" onclick="showPage('insurance')" >{{ __('insurance') }}</a></li>
        <li class="active">{{ $insurance->name }}</li>
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
                    width="100px" style="height: 100px" src="{{ $insurance->url }}" alt="insurance picture">

                <h3 class="profile-username text-center">{{ $insurance->name }}</h3>
 

                <ul class="list-group list-group-unbordered">


                    <li class="list-group-item">
                        <b>{{ __('doctors') }}</b> <a class="pull-right">{{ number_format($insurance->doctor_insurances()->count()) }}</a>
                    </li>

                    <li class="list-group-item">
                        <b>{{ __('pharmacies') }}</b> <a class="pull-right">{{ number_format($insurance->pharmacy_insurances()->count()) }}</a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>{{ __('labs') }}</b> <a class="pull-right">{{ number_format($insurance->lab_insurances()->count()) }}</a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>{{ __('radiologies') }}</b> <a class="pull-right">{{ number_format($insurance->radiology_insurances()->count()) }}</a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>{{ __('patients') }}</b> <a class="pull-right">{{ number_format($insurance->patients()->count()) }}</a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>{{ __('users') }}</b> <a class="pull-right">{{ number_format($insurance->user_insurances()->count()) }}</a>
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
                @if ($insurance->name_ar)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_ar') }}</strong> 
                <p class="text-muted">
                    {{ $insurance->name_ar }}
                </p>
                <hr>
                @endif

                @if ($insurance->name_fr)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_fr') }}</strong> 
                <p class="text-muted">
                    {{ $insurance->name_fr }}
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
            center: {lat: {{ $insurance->lat }}, lng: {{ $insurance->lng }} },
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
        
        
        placeMarker(new google.maps.LatLng({{ $insurance->lat }}, {{ $insurance->lng }}), map);

    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&callback=initMap"
async defer></script>
@endsection
