@extends("layout.app", ["title" => $clinic->name])

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
        <li><a href="#" onclick="showPage('clinic')" >{{ __('clinics') }}</a></li>
        <li><a href="#" onclick="showPage('doctor/show/{{ $clinic->doctor_id }}')" >{{__('dr.')  }} {{ optional($clinic->doctor)->name }}</a></li>
        <li class="active">{{ optional($clinic->city)->name }}-{{ optional($clinic->area)->name }}</li>
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
                    width="100px" style="height: 100px" src="{{ $clinic->url }}" alt="clinic picture">

                <h3 class="profile-username text-center">{{ optional($clinic->doctor)->name }}</h3>
                

                <p class="text-muted text-center">
                    @if ($clinic->active == 1)
                    <span class="label-sm label label-success" >{{ __('active') }}</span>
                    @else
                    <span class="label-sm label label-danger" >{{ __('not active') }}</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered">


                    <li class="list-group-item">
                        <b>{{ __('availability') }}</b> 
                        <a class="pull-right">
                            @if ($clinic->availability)
                            <span class="w3-text-green w3-large fa fa-toggle-on" > </span>
                            @else
                            <span class="w3-text-red w3-large fa fa-toggle-on" > </span>
                            @endif
                        </a>
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
                @if ($clinic->active)
                <div class="w3-display-container" >
                    <strong><i class="fa fa-circle-o margin-r-5"></i> {{ __('active') }}</strong> 
                    <p class="text-muted w3-left">
                        {{ $clinic->fees }}
                    </p>
                    <hr>
                </div>
                @endif
                 
                @if ($clinic->fees)
                <strong><i class="fa fa-money margin-r-5"></i> {{ __('fees') }}</strong> 
                <p class="text-muted">
                    {{ $clinic->fees }}
                </p>
                <hr>
                @endif
                
                @if ($clinic->fees2)
                <strong><i class="fa fa-money margin-r-5"></i> {{ __('fees2') }}</strong> 
                <p class="text-muted">
                    {{ $clinic->fees2 }}
                </p>
                <hr>
                @endif
                
                @if ($clinic->waiting_time)
                <strong><i class="fa fa-clock-o margin-r-5"></i> {{ __('waiting_time') }}</strong> 
                <p class="text-muted">
                    {{ $clinic->waiting_time }}
                </p>
                <hr>
                @endif
                
                @if ($clinic->city_id)
                <strong><i class="fa fa-building margin-r-5"></i> {{ __('city') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($clinic->city)->name_ar }}" >{{ optional($clinic->city)->name }}</a>
                </p>
                <hr>
                @endif

                @if ($clinic->area_id)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('area') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($clinic->area)->name_ar }}+{{ optional($clinic->city)->name_ar }}" >{{ optional($clinic->area)->name }}</a>
                </p>
                <hr>
                @endif
                
                @if ($clinic->phone)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $clinic->phone }}" >{{ $clinic->phone }}</a>
                </p>
                <hr>
                @endif
                  
                @if ($clinic->available_days)
                <strong><i class="fa fa-calendar margin-r-5"></i> {{ __('available_days') }}</strong> 
                <p class="text-muted">
                    {{ $clinic->available_days }}
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
                <li class="active"><a href="#infoTab" data-toggle="tab">{{ __('clinic info') }}</a></li> 
                <li class=""><a href="#workingHoursTab" data-toggle="tab">{{ __('working hours') }}</a></li> 
                <li class=""><a href="#ordersTab" data-toggle="tab">{{ __('current week orders') }}</a></li> 
            </ul>
            <div class="tab-content">
                
                <div class="active tab-pane" id="infoTab">
                    <ul class="w3-ul" >
                        <li class="w3-large w3-padding cursor" onclick="$('.clinic-chart-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('reservations graph')  }}</b>
                        </li>
                        <li class="clinic-chart-list-item" > 
                            @if (count($clinic->getChartData()) > 0) 
                            <canvas  id="chartDiv{{ $clinic->id }}" class="w3-block" style="width: 100%; height: 300px;"></canvas> 
                            @endif
                        </li>
                        <li class="w3-large w3-padding cursor" onclick="$('.clinic-map-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('clinic location')  }}</b>
                        </li>
                        <li class="clinic-map-list-item" >
                            <div id="map" class="w3-block" style="height:400px" ></div>
                        </li>
                    </ul>
                    
                    <br>
                </div> 


                <div class="tab-pane" id="workingHoursTab">
                    <ul class="w3-ul" > 
                        <li class="w3-large w3-padding cursor" onclick="$('.clinic-working-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('working hours')  }}</b>
                        </li>
                        
                        <li class="clinic-working-list-item" >
                            <table class="table table-bordered text-center" >
                                <tr>
                                    <th>{{ __('day') }}</th> 
                                    <th colspan="2" >{{ __('part_1') }}</th> 
                                    <th colspan="2" >{{ __('part_2') }}</th> 
                                    <th colspan="2" >{{ __('reservations_number') }}</th> 
                                </tr>
                                @foreach($clinic->working_hours()->get() as $item)
                                @if ($item->active)
                                <tr  class=""  >
                                    <td>{{ App\WorkingHours::getDayName($item->day) }}</td> 
                                    <td class="{{ ($item->part1_from == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ $item->part1_from }}</td> 
                                    <td class="{{ ($item->part1_from == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ $item->part1_to }}</td>

                                    <td class="{{ ($item->part2_from == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ ($item->part2_from != '00:00:00')? $item->part2_from : '-' }}</td> 
                                    <td class="{{ ($item->part2_to == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ ($item->part2_to != '00:00:00')? $item->part2_to : '-' }}</td>

                                    <td>{{ $item->reservation_number_1 }}</td>
                                    <td>{{ $item->reservation_number_2 }}</td>
                                </tr>
                                @else
                                <tr  class="w3-red"  >
                                    <td>{{ App\WorkingHours::getDayName($item->day) }}</td> 
                                    <td colspan="6" >{{ __('not work at this day') }}</td>   
                                </tr>
                                @endif
                                @endforeach
                            </table>
                        </li>
                        
                        <li class="w3-large w3-padding cursor" onclick="$('.clinic-freeDay-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('free days')  }}</b>
                        </li>
                        
                        <li class="clinic-freeDay-list-item" >
                            
                            <div class="w3-row" >
                                    @foreach($clinic->free_days as $item)
                                <div class="w3-col l3 m3 s6" style="padding: 3px" >
                                    <div class="w3-padding shadow w3-round w3-dark-doctoraak text-center " >
                                        <div style="margin: 4px" >
                                            <!--
                                            F j, Y, g:i a
                                            -->
                                            {{ date("F j, Y", strtotime($item['date'])) }}
                                        </div>
                                        <div>
                                            @if ($item['part_id'] == 1)
                                            <span class="label w3-white w3-round-large" >{{ __('part 1') }}</span>
                                            @elseif ($item['part_id'] == 2)
                                            <span class="label w3-white w3-round-large" >{{ __('part 2') }}</span>
                                            @elseif ($item['part_id'] == 3)
                                            <span class="label w3-white w3-round-large" >{{ __('part1 && part2') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                    @endforeach
                            </div>
                            
                        </li>
                        
                    </ul>
                    
                    <br>
                    
                </div> 
                
                
                <div class="tab-pane" id="ordersTab">
                    <ul class="w3-ul" > 
                        <li class="w3-large w3-padding cursor" onclick="$('.clinic-orders-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('working hours')  }}</b>
                        </li>
                        <li class="clinic-orders-list-item" >

                            <table class="table table-bordered" id="orderTable" >
                                <thead>
                                    <tr class="w3-dark-doctoraak" >
                                        <th></th>
                                        <th>{{ __('patient') }}</th>
                                        <th>{{ __('date') }}</th>
                                        <th>{{ __('part') }}</th>
                                        <th>{{ __('reservation_number') }}</th>
                                        <th>{{ __('type') }}</th>
                                        <th>{{ __('notes') }}</th>
                                    </tr>
                                </thead>
                                @foreach($clinic->orders()->whereBetween('date', App\WorkingHours::getStartAndEndDateOfWeek(date('Y-m-d')))->get() as $item) 
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ optional($item->patient)->url }}" style="width: 30px;height: 30px" class="w3-circle" >
                                        </td>
                                        <td>
                                            <a href="#" class="w3-text-blue" onclick="showPage('patient/show/{{ $item->patient_id }}')" >{{ optional($item->patient)->name }}</a>
                                        </td>
                                        <td>
                                            {{ $item->date }}
                                            <br>
                                            <span class="w3-text-dark-doctoraak" >({{ date("F j, Y", strtotime($item->date)) }})</span>
                                        </td>
                                        <td>{{ $item->part_id }}</td>
                                        <td>{{ $item->reservation_number }}</td>
                                        <td>{{ $item->getReservationTypeAr() }}</td>
                                        <td> 
                                            {!! str_replace(",", "<br>", $item->notes) !!} 
                                        </td> 
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </li>
                    </ul>
                        
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js" ></script>

@if (count($clinic->getChartData()) > 0)
<script>
var ctx{{ $clinic->id }} = document.getElementById('chartDiv{{ $clinic->id }}').getContext('2d');
var myChart{{ $clinic->id }} = new Chart(ctx{{ $clinic->id }}, {
    type: 'line',
    data: {
        labels: [
          @foreach($clinic->getChartData() as $key => $value)
            '{{ $key }}', 
          @endforeach
        ],
        datasets: [{
            label: '{{ __("clinic reservations") }}',
            data: [
                @foreach($clinic->getChartData() as $key => $value)
                "{{ $value }}",
                @endforeach
            ],
            backgroundColor: [
                'rgba(142, 57, 250, 0.5)', 
            ],
            borderColor: [
                'rgba(105, 60, 151, 1)', 
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

@endif


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
            center: {lat: {{ $clinic->lat }}, lng: {{ $clinic->lng }} },
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
 
            map.panTo(position);
        }
        
        
        placeMarker(new google.maps.LatLng({{ $clinic->lat }}, {{ $clinic->lng }}), map);

    }
    
    
    $(document).ready(function(){
        $('#orderTable').DataTable({ 
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print'
            ],  
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&callback=initMap"
async defer></script>
@endsection
