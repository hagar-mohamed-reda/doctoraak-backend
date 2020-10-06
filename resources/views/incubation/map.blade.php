@extends("layout.app", ["title" => "المرضى"])
 
@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li><a href="#" onclick="showPage('incubation')" >{{ __('incubation') }}</a></li> 
        <li class="active">{{  __('incubation locations') }}</li>
    </ol>
</div>
@endsection

@section("out")

<div class="row w3- ">
    <!-- /.col -->
    <div class="col-md-3"   id="filter" >
        <!-- Profile Image -->
        <div class="box box-primary">
                <div class="box-header with-border w3-large text-center" >
                    {{ __('filter') }}
                </div>
            <div class="box-body box-profile">
                 
                <div class="  "> 
                    <div class=" " >
                        <br>
                        <div class="w3-row" >
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with incubation info') }}</label>
                                <input type="search" class="form-control"  v-model="filter.search_string"  > 
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with bed number') }}</label>
                                <input type="number" class="form-control"  v-model="filter.bed_number"  > 
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('city') }}</label>
                                <select class="form-control" v-model="filter.city_id"  > 
                                    @foreach(App\City::all() as $item)
                                    <option value="{{ $item->id }}" >{{ $item->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('area') }}</label>
                                <select class="form-control" v-model="filter.area_id"  > 
                                    @foreach(App\Area::all() as $item)
                                    <option value="{{ $item->id }}" v-if="filter.city_id=='{{ $item->city_id }}'" >{{ $item->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="w3-col l12 m12 s12 w3-padding" >  
                                <label for="rate">rate</label>  
                                <input type="hidden" name="rate" id="rate">
                                <div class="" id="filterRate">

                                </div>  
                            </div> 
                        </div>
                    </div>
                </div> 

                <a href="#" class="btn btn-primary btn-block " onclick="filterResult()"  > <b> {{ __('search') }} </b> </a>
                <a href="#" class="btn btn-default btn-block " onclick="showAll()" > <b> {{ __('show all') }} </b> </a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
        
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#mapTab" data-toggle="tab">{{ __('locations on map') }}</a></li> 
            </ul>
            <div class="tab-content">

                <div class="active tab-pane" id="mapTab">
                    <div id="map" class="w3-block" style="height:500px" ></div>
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
    var filterRate = null;
    var filter = null;
    
    
     
    
    function filterResult() { 
        showPage('incubation/map?' + $.param(filter.filter));       
    }

    function showAll() {
        filter.filter = {};
        filterRate.rate(0);
        filterResult();
    }
 
    function reload() {
        //
    }

    filter = new Vue({
        el: '#filter',
        data: {
            filter: {
            }
        },
        methods: {
        },
        computed: {
        },
        watch: {
        }
    });
    
    $(document).ready(function(){
        
        filterRate = new Ratebar(document.getElementById('filterRate'));
        filterRate.setOnRate(function(){
            filter.filter.rate = filterRate.value;
        }); 
    });
    
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
        @php 
            $firstIncubation = App\Incubation::where('lat', '!=', null)->first();
        @endphp
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{ $firstIncubation->lat }}, lng: {{ $firstIncubation->lng }} },
            zoom: 12
        });

        google.maps.event.addListener(map, 'click', function (e) {
            placeMarker(e.latLng, map);
        });

        placeMarker = function (position, map) { 
            new google.maps.Marker({
                position: position,
                map: map
            }); 
            map.panTo(position);
        }
        
        @foreach($resources as $resource)
        @if ($resource->lat && $resource->lng)
            placeMarker(new google.maps.LatLng({{ $resource->lat }}, {{ $resource->lng }}), map);
        @endif
        @endforeach
    }
    
    
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&callback=initMap"
async defer></script>
@endsection
