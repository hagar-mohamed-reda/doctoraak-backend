@extends("layout.app", ["title" => "المرضى"])
 
@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li><a href="#" onclick="showPage('radiology')" >{{ __('radiology') }}</a></li> 
        <li class="active">{{  __('radiology locations') }}</li>
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
                        <b>{{ __('radiology_numbers') }}</b> 
                        <a class="pull-right">
                            {{ count($resources) }}
                        </a>
                    </li>
                </ul>
                <div class="  ">  
                            <br>
                    <div class="w3-row" >
                        <div class="" > 
                            <radiologyel>{{ __('search with doctor info') }}</radiologyel>
                            <input type="search" class="form-control"  v-model="filter.search_string"  > 
                        </div>
                        <div class="" > 
                            <radiologyel>{{ __('insurance companies') }}</radiologyel>
                            <select class="form-control select2 filter_insurance_id" multiple=""  onchange="filter.filter.insurance_id=$('.filter_insurance_id').val()"  >
                                <!--
                                <option value=""  >{{ __('all') }}</option>
                                -->
                                @foreach(App\Insurance::all() as $item)
                                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="" > 
                            <radiologyel>{{ __('radiology_doctor') }}</radiologyel>
                            <select class="form-control" v-model="filter.radiology_doctor_id"  >
                                <!--
                                <option value=""  >{{ __('all') }}</option>
                                -->
                                @foreach(App\LabDoctor::all() as $item)
                                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="" > 
                            <radiologyel>{{ __('city') }}</radiologyel>
                            <select class="form-control" v-model="filter.city_id"  > 
                                @foreach(App\City::all() as $item)
                                <option value="{{ $item->id }}" >{{ $item->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="" > 
                            <radiologyel>{{ __('area') }}</radiologyel>
                            <select class="form-control" v-model="filter.area_id"  > 
                                @foreach(App\Area::all() as $item)
                                <option value="{{ $item->id }}" v-if="filter.city_id=='{{ $item->city_id }}'" >{{ $item->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>  
                        <div class="" >
                            <radiologyel>{{ __('active') }}</radiologyel>
                            <select class="form-control" has-select2="off" v-model="filter.active"  > 
                                <option value="1"  >{{ __('active') }}</option>
                                <option value="2"  >{{ __('not active') }}</option>
                            </select>
                        </div>  
                        <div class="" >
                            <radiologyel>{{ __('delivery') }}</radiologyel>
                            <select class="form-control" has-select2="off" v-model="filter.delivery"  > 
                                <option value="1"  >{{ __('on') }}</option>
                                <option value="2"  >{{ __('off') }}</option>
                            </select>
                        </div>   
                        <br>
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
        showPage('radiology/map?' + $.param(filter.filter));       
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
                radiology_doctor_id: '{{ request()->radiology_doctor_id }}',
                city_id: '{{ request()->city_id }}',
                area_id: '{{ request()->area_id }}',
                active: '{{ request()->active }}',
                delivery: '{{ request()->delivery }}',
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
           
        $('.radiology_doctor_id').val('{{ request()->radiology_doctor_id }}');
        $('.insurance_id').val('{{ request()->insurance_id }}');
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
            $firstLab = App\Lab::where('lat', '!=', null)->first();
        @endphp
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{ $firstLab->lat }}, lng: {{ $firstLab->lng }} },
            zoom: 12
        });

        /*google.maps.event.addListener(map, 'click', function (e) {
            placeMarker(e.latLng, map);
        });*/

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
