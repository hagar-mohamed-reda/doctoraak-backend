@extends("layout.app", ["title" => $radiology->name])

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

    .radiology-order-item {
        display: block;
    }

</style>

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li>
        <li><a href="#" onclick="showPage('radiology')" >{{ __('radiologys') }}</a></li> 
        <li class="active">{{ $radiology->name }}</li>
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
                    width="100px" style="height: 100px" src="{{ $radiology->url }}" alt="radiology picture">

                <h3 class="profile-username text-center">{{ $radiology->name }}</h3>
                <h3 class="profile-username text-center">{{ optional($radiology->doctor)->name }}</h3>
                

                <p class="text-muted text-center">
                    @if ($radiology->active == 1)
                    <span class="radiologyel-sm radiologyel radiologyel-success" >{{ __('active') }}</span>
                    @else
                    <span class="radiologyel-sm radiologyel radiologyel-danger" >{{ __('not active') }}</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered">


                    <li class="list-group-item">
                        <b>{{ __('orders') }}</b> 
                        <a class="pull-right">
                            {{ number_format($radiology->orders()->count()) }}
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
            
                <strong><i class="fa fa-motorcycle margin-r-5"></i> {{ __('delivery') }}</strong> 
                <p class="text-muted">
                    @if ($radiology->delivery == 1)
                    <i class="fa fa-toggle-on w3-text-green" ></i>
                    @else
                    <i class="fa fa-toggle-on w3-text-gray" ></i>
                    @endif 
                </p>
                <hr>
                
                @if ($radiology->name_ar)
                <strong><i class="fa fa-medkit margin-r-5"></i> {{ __('name_ar') }}</strong> 
                <p class="text-muted">
                    {{ $radiology->name_ar }}
                </p>
                <hr>
                @endif
                @if ($radiology->name_fr)
                <strong><i class="fa fa-medkit margin-r-5"></i> {{ __('name_fr') }}</strong> 
                <p class="text-muted">
                    {{ $radiology->name_fr }}
                </p>
                <hr>
                @endif
                
                @if ($radiology->email)
                <strong><i class="fa fa-envelope margin-r-5"></i> {{ __('email') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="mailto:{{ $radiology->email }}" >{{ $radiology->email }}</a>
                </p>
                <hr>
                @endif    
                
                @if ($radiology->phone)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $radiology->phone }}" >{{ $radiology->phone }}</a>
                </p>
                <hr>
                @endif
                
                @if ($radiology->phone2)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone2') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $radiology->phone2 }}" >{{ $radiology->phone2 }}</a>
                </p>
                <hr>
                @endif
                
                @if ($radiology->city_id)
                <strong><i class="fa fa-building margin-r-5"></i> {{ __('city') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($radiology->city)->name_ar }}" >{{ optional($radiology->city)->name }}</a>
                </p>
                <hr>
                @endif

                @if ($radiology->area_id)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('area') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($radiology->area)->name_ar }}+{{ optional($radiology->city)->name_ar }}" >{{ optional($radiology->area)->name }}</a>
                </p>
                <hr>
                @endif
                
                @if ($radiology->avairadiologyle_days)
                <strong><i class="fa fa-calendar margin-r-5"></i> {{ __('avairadiologyle_days') }}</strong> 
                <p class="text-muted">
                    {{ $radiology->avairadiologyle_days }}
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
                <li class="active"><a href="#infoTab" data-toggle="tab">{{ __('radiology info') }}</a></li> 
                <li class=""><a href="#workingHoursTab" data-toggle="tab">{{ __('working hours') }}</a></li> 
                <li class=""><a href="#ordersTab" data-toggle="tab">{{ __('current week orders') }}</a></li> 
                <li><a href="#sendMessage" data-toggle="tab"><i class="fa fa-envelope" style="padding: 4px" ></i>{{ __('send message to radiology') }}</a></li> 
            </ul>
            <div class="tab-content">
                
                <div class="active tab-pane" id="infoTab">
                    <ul class="w3-ul" >
                        <li class="w3-large w3-padding cursor" onclick="$('.radiology-chart-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('orders graph')  }}</b>
                        </li>
                        <li class="radiology-chart-list-item" > 
                            @if (count($radiology->getChartData()) > 0) 
                            <canvas  id="chartDiv{{ $radiology->id }}" class="w3-block" style="width: 100%; height: 300px;"></canvas> 
                            @endif
                        </li>
                        <li class="w3-large w3-padding cursor" onclick="$('.radiology-map-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('radiology location')  }}</b>
                        </li>
                        <li class="radiology-map-list-item" >
                            <div id="map" class="w3-block" style="height:400px" ></div>
                        </li>
                    </ul>
                    
                    <br>
                </div> 


                <div class="tab-pane" id="workingHoursTab">
                    <ul class="w3-ul" > 
                        <li class="w3-large w3-padding cursor" onclick="$('.radiology-working-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('working hours')  }}</b>
                        </li>
                        
                        <li class="radiology-working-list-item" >
                            <table class="table table-bordered text-center" >
                                <tr>
                                    <th>{{ __('day') }}</th> 
                                    <th colspan="2" >{{ __('part') }}</th>  
                                </tr>
                                @foreach($radiology->working_hours()->get() as $item)
                                @if ($item->active)
                                <tr  class=""  >
                                    <td>{{ App\WorkingHours::getDayName($item->day) }}</td> 
                                    <td class="{{ ($item->part_from == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ $item->part_from }}</td> 
                                    <td class="{{ ($item->part_from == '00:00:00')? 'w3-dark-gray' : 'w3-doctoraak' }}" >{{ $item->part_to }}</td>
  
                                </tr>
                                @else
                                <tr  class="w3-red"  >
                                    <td>{{ App\WorkingHours::getDayName($item->day) }}</td> 
                                    <td colspan="3" >{{ __('not work at this day') }}</td>   
                                </tr>
                                @endif
                                @endforeach
                            </table>
                        </li>
                        
                        
                        
                        <li class="radiology-freeDay-list-item" >
                            
                            <div class="w3-row" >
                                   
                            </div>
                            
                        </li>
                        
                    </ul>
                    
                    <br>
                    
                </div> 
                
                
                <div class="tab-pane" id="ordersTab">
                    <ul class="w3-ul" > 
                        <li class="w3-large w3-padding cursor" onclick="$('.radiology-orders-list-item').slideToggle(400)" >
                            <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('working hours')  }}</b>
                        </li>
                        <li class="radiology-orders-list-item" >
                            <table class="table table-bordered" id="orderTable" >
                                <thead>
                                    <tr class="w3-dark-radiologyaak" >
                                        <th></th>
                                        <th>{{ __('patient') }}</th>
                                        <th>{{ __('attachment') }}</th> 
                                        <th>{{ __('notes') }}</th>
                                        <th>{{ __('insurance_accept') }}</th>
                                        <th>{{ __('insurance_code') }}</th>
                                        <th>{{ __('order date') }}</th>
                                    </tr>
                                </thead>
                               
                                @php    
                                    $dates = App\WorkingHours::getStartAndEndDateOfWeek(date('Y-m-d'));
                                    $orders = $radiology->orders()->select(DB::raw('*'))
                                                ->whereRaw("Date(created_at) between '" . $dates[0] . "' and '" . $dates[1] . "'")->get();
                                @endphp
                                @foreach($orders as $item) 
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ optional($item->patient)->url }}" style="width: 30px;height: 30px" class="w3-circle" >
                                        </td>
                                        <td>
                                            <a href="#" class="w3-text-blue" onclick="showPage('patient/show/{{ $item->patient_id }}')" >{{ optional($item->patient)->name }}</a>
                                        </td>
                                        <td>
                                            @if ($item->photo)
                                            <img src="{{ $item->getJson()->photo }}" style="width: 30px;height: 30px" class="w3-circle" >
                                            @endif
                                        </td>
                                        <td> 
                                            {!! str_replace(",", "<br>", $item->notes) !!} 
                                        </td> 
                                        <td>{{ $item->insurance_accept }}</td>
                                        <td>{{ $item->insurance_code }}</td>
                                        <td>
                                            {{ $item->created_at }}
                                            <br>
                                            <span class="w3-text-dark-doctoraak" >({{ date("F j, Y", strtotime($item->created_at)) }})</span>
                                        </td> 
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </li>
                    </ul>
                        
                </div> 
                
                <div class="tab-pane" id="sendMessage">
                    <form class="form-horizontal form" method="post" action="{{ url('/notification/send-message') }}" >
                        @csrf
                        
                        <input type="hidden" name="user_id" value="{{ $radiology->id }}"  >
                        <input type="hidden" name="user_type" value="RADIOLOGY"  >
                        <input type="hidden" name="tokens[]" value="{{ $radiology->firebase_token }}"  > 
                        <div class="form-group">
                            <radiologyel for="inputName" class="col-sm-2 control-radiologyel">{{ __('title_ar') }}</radiologyel>  
                            <div class="col-sm-10">
                                <input type="text" name="title_ar" class="form-control" placeholder="{{ __('title_ar') }}" required="" >
                            </div>
                        </div> 
                        <div class="form-group">
                            <radiologyel for="inputName" class="col-sm-2 control-radiologyel">{{ __('title_en') }}</radiologyel>  
                            <div class="col-sm-10">
                                <input type="text" name="title_en" class="form-control" placeholder="{{ __('title_en') }}" required="">
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <radiologyel for="inputExperience" class="col-sm-2 control-radiologyel">{{ __('message_ar') }}</radiologyel>

                            <div class="col-sm-10">
                                <textarea class="form-control" name="message_ar" id="inputExperience" required="" placeholder="{{ __('message_ar') }}"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <radiologyel for="inputExperience" class="col-sm-2 control-radiologyel">{{ __('message_en') }}</radiologyel>

                            <div class="col-sm-10">
                                <textarea class="form-control" name="message_en" id="inputExperience" required="" placeholder="{{ __('message_en') }}"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-danger">{{ __('send') }}</button>
                            </div>
                        </div>
                    </form>
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
<script src="{{ url('/js/Chart.js') }}" ></script>

@if (count($radiology->getChartData()) > 0)
<script>
var ctx{{ $radiology->id }} = document.getElementById('chartDiv{{ $radiology->id }}').getContext('2d');
var myChart{{ $radiology->id }} = new Chart(ctx{{ $radiology->id }}, {
    type: 'line',
    data: {
        radiologyels: [
          @foreach($radiology->getChartData() as $key => $value)
            '{{ $key }}', 
          @endforeach
        ],
        datasets: [{
            radiologyel: '{{ __("radiology orders") }}',
            data: [
                @foreach($radiology->getChartData() as $key => $value)
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
            center: {lat: {{ $radiology->lat }}, lng: {{ $radiology->lng }} },
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
        
        
        placeMarker(new google.maps.LatLng({{ $radiology->lat }}, {{ $radiology->lng }}), map);

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
