@extends("layout.app", ["title" => "المرضى"])
 
@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li><a href="#" onclick="showPage('clinic')" >{{ __('clinic') }}</a></li> 
        <li class="active">{{  __('clinic locations') }}</li>
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
                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">
                        <b>{{ __('clinic_numbers') }}</b> 
                        <a class="pull-right">
                            {{ count($resources) }}
                        </a>
                    </li>
                </ul>
                <div class="  "> 
                    <div class=" " >
                        <br>
                        <div class="w3-row" >
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with clinic info') }}</label>
                                <input type="search" class="form-control"  v-model="filter.search_string"  > 
                            </div> 
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with fees') }}</label>
                                <input type="number" class="form-control"  v-model="filter.fees"  > 
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with fees2') }}</label>
                                <input type="number" class="form-control"  v-model="filter.fees2"  > 
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('search with waiting time') }}</label>
                                <input type="number" class="form-control"  v-model="filter.waiting_time"  > 
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('doctor') }}</label>
                                <select class="form-control select2 doctor_id" onchange="filter.filter.doctor_id = this.value"  > 
                                    <option value="" >{{ __('all') }}</option>
                                    @foreach(App\Doctor::all() as $item)
                                    <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w3-col l12 m12 s12 w3-padding" > 
                                <label>{{ __('city') }}</label>
                                <select class="form-control select2 city_id"  onchange="filter.filter.city_id = this.value"  > 
                                    <option value="" >{{ __('all') }}</option>
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
                                <label>{{ __('active') }}</label>
                                <select class="form-control" has-select2="off" v-model="filter.active"  > 
                                    <option value="1"  >{{ __('active') }}</option>
                                    <option value="2"  >{{ __('not active') }}</option>
                                </select>
                            </div> 
                            <div class="w3-col l12 m12 s12 w3-padding" >
                                <label>{{ __('availability') }}</label>
                                <select class="form-control" has-select2="off" v-model="filter.availability"  > 
                                    <option value="1"  >{{ __('available') }}</option>
                                    <option value="2"  >{{ __('not available') }}</option>
                                </select>
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
    var filter = null;
    
    
     
    
    function filterResult() { 
        showPage('clinic/map?' + $.param(filter.filter));       
    }

    function showAll() {
        filter.filter = {}; 
        filterResult();
    }
 
    function reload() {
        //
    }

    filter = new Vue({
        el: '#filter',
        data: {
            filter: {
                search_key: '{{ request()->search_key }}',
                fees: '{{ request()->fees }}',
                fees2: '{{ request()->fees2 }}',
                waiting_time: '{{ request()->waiting_time }}',
                doctor_id: '{{ request()->doctor_id }}',
                city_id: '{{ request()->city_id }}',
                area_id: '{{ request()->area_id }}',
                active: '{{ request()->active }}',
                availability: '{{ request()->availability }}',
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
           
        $('.doctor_id').val('{{ request()->doctor_id }}');
        $('.city_id').val('{{ request()->city_id }}');

        $('.select2').select2();
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
            $firstClinic = App\Clinic::where('lat', '!=', null)->first();
        @endphp
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{ $firstClinic->lat }}, lng: {{ $firstClinic->lng }} },
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
