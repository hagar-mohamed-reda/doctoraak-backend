@extends("layout.app", ["title" => $doctor->name])

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

    .cv {
        display: none;
    }
    
    .doctor-cv {
        display: block
    }
</style>

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li>
        <li><a href="#" onclick="showPage('doctor')" >{{ __('doctor') }}</a></li>
        <li class="active">{{ $doctor->name }}</li>
    </ol>
</div>
@endsection

@section("out")

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile w3-display-container">
                <img 
                    class="profile-user-img img-responsive img-circle" 
                    onclick="viewImage(this)" 
                    width="100px" style="height: 100px" src="{{ $doctor->url }}" alt="doctor picture">
                
                <h3 class="profile-username text-center">
                    
                    <a class="w3-text-blue" target="_blank" 
                       href="http://www.google.com/search?q=doctor+{{ $doctor->name }}+{{ optional($doctor->specialization)->name }}+specialist" >
                        {{ $doctor->name }}
                    </a>
                </h3>

                <p class="text-muted text-center"> 
                    <a class="w3-text-blue" target="_blank" href="http://www.google.com/search?q={{ optional($doctor->specialization)->name }}+specialist" >{{ optional($doctor->specialization)->name }}</a>
                </p>

                <ul class="list-group list-group-unbordered">
 
                    <li class="list-group-item">
                        <b>{{ __('reservation_rate') }}</b> 
                        <a class="pull-right">
                            @for($i = 0; $i < 5; $i ++)
                            @if ($i < $doctor->reservation_rate)
                            <i class="fa fa-star w3-text-orange" ></i>
                            @else 
                            <i class="fa fa-star w3-text-gray" ></i>
                            @endif
                            @endfor
                        </a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>{{ __('degree_rate') }}</b> 
                        <a class="pull-right">
                            @for($i = 0; $i < 5; $i ++)
                            @if ($i < $doctor->degree_rate)
                            <i class="fa fa-star w3-text-orange" ></i>
                            @else 
                            <i class="fa fa-star w3-text-gray" ></i>
                            @endif
                            @endfor
                        </a>
                    </li>
                   
                    <li class="list-group-item">
                        <b>{{ __('clinic numbers') }}</b> 
                        <a class="pull-right">
                            {{ number_format($doctor->clinics()->count()) }}
                        </a>
                    </li>

                    @foreach($doctor->clinics()->get() as $item)
                    <li class="list-group-item">
                        <b>{{ __('clinic') }} {{ $loop->iteration }} {{ __('reservations') }}</b> 
                        <a class="pull-right">
                            {{ number_format($item->orders()->count()) }}
                        </a>
                    </li>
                    @endforeach
                    
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
        
                @if ($doctor->degree_id)
                <strong><i class="fa fa-graduation-cap margin-r-5"></i> {{ __('degree') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" target="_blank" href="http://www.google.com/search?q={{ optional($doctor->degree)->name }}" >{{ optional($doctor->degree)->name }}</a>
                </p>
                <hr>
                @endif
                
                @if ($doctor->name_ar)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_ar') }}</strong> 
                <p class="text-muted">
                    {{ $doctor->name_ar }}
                </p>
                <hr>
                @endif

                @if ($doctor->name_fr)
                <strong><i class="fa fa-bank margin-r-5"></i> {{ __('name_fr') }}</strong> 
                <p class="text-muted">
                    {{ $doctor->name_fr }}
                </p>
                <hr>
                @endif
                
                @if ($doctor->email)
                <strong><i class="fa fa-envelope margin-r-5"></i> {{ __('email') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="mailto:{{ $doctor->email }}" >{{ $doctor->email }}</a>
                </p>
                <hr>
                @endif                
                
                @if ($doctor->phone)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $doctor->phone }}" >{{ $doctor->phone }}</a>
                </p>
                <hr>
                @endif                
                
                @if ($doctor->phone2)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone2') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $doctor->phone2 }}" >{{ $doctor->phone2 }}</a>
                </p>
                <hr>
                @endif                

                @if ($doctor->title)
                <strong><i class="fa fa-edit margin-r-5"></i> {{ __('title') }}</strong> 
                <p class="text-muted">
                    {{ $doctor->title }}
                </p>
                <hr>
                @endif 

                @if ($doctor->cv)
                <strong><i class="fa fa-address-card margin-r-5"></i> {{ __('doctor cv') }}</strong> 
                <p class="text-muted">
                    <a target="_blank" href="https://docs.google.com/viewerng/viewer?url={{ $doctor->cv_url }}" >{{ __('view') }}</a>
                </p>
                <hr>
                @endif
                
                @if ($doctor->cv2)
                <strong><i class="fa fa-address-card margin-r-5"></i> {{ __('updated cv') }}</strong> 
                <p class="text-muted">
                    <a target="_blank" href="https://docs.google.com/viewerng/viewer?url={{ $doctor->cv2_url }}" >{{ __('view') }}</a>
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
                @foreach($doctor->clinics()->get() as $item)
                <li class="{{ $loop->iteration == 1? 'active': '' }}"><a href="#clinicTab{{ $item->id }}" data-toggle="tab">{{ __('clinic') }} {{ $loop->iteration }}</a></li> 
                @endforeach
                <li><a href="#sendMessage" data-toggle="tab"><i class="fa fa-envelope" style="padding: 4px" ></i>{{ __('send message to doctor') }}</a></li> 
            </ul>
            <div class="tab-content">

                @foreach($doctor->clinics()->get() as $item)
                <div class="{{ $loop->iteration == 1? 'active': '' }} tab-pane" id="clinicTab{{ $item->id }}">
                    
                    <div class="w3-block w3-round w3-padding" style="direction: ltr;height: 100px;background-image: url({{ url('/image/clinicheader.png') }});background-size: auto 100%" > 
                        <br>
                        <img src="{{ $item->url }}" class="w3-circle shadow" height="60px" width="60px" style="margin-top: 30px" >
                        <br>
                        <a href="#" class="w3-text-blue" onclick="showPage('clinic/show/{{ $item->id }}')" >{{ __('show more') }}</a>
                         
                    </div>
                    <br>
                    <div class="w3-row" >
                        <div class="w3-col l3 m3 s12 w3-border-left w3-border-gray box-primary" >
                            <ul class="" >
                                @if ($item->availability)
                                <li>
                                    <strong>  
                                    @if ($item->availability)
                                        <span class="fa fa-check-circle w3-text-green  margin-r-5" ></span>
                                        {{ __('available today') }} 
                                    @else
                                        <span class="fa fa-check-circle w3-text-red margin-r-5" ></span>
                                        {{ __('not available today') }} 
                                    @endif
                                    </strong> 
                                     
                                    <hr>
                                </li>
                                @endif 
                                @if ($item->fees)
                                <li>
                                    <strong><i class="fa fa-money margin-r-5"></i> {{ __('fees') }}</strong> 
                                    <p class="text-muted">
                                        {{ $item->fees }}
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                @if ($item->fees2)
                                <li>
                                    <strong><i class="fa fa-money margin-r-5"></i> {{ __('fees2') }}</strong> 
                                    <p class="text-muted">
                                        {{ $item->fees2 }}
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                
                                @if ($item->waiting_time)
                                <li>
                                    <strong><i class="fa fa-clock-o margin-r-5"></i> {{ __('waiting_time') }}</strong> 
                                    <p class="text-muted">
                                        {{ $item->waiting_time }}
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                
                                @if ($item->address)
                                <li>
                                    <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('address') }}</strong> 
                                    <p class="text-muted">
                                        <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ $item->address }}" >{{ $item->address }}</a> 
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                
                                @if ($item->city()->first())
                                <li>
                                    <strong><i class="fa fa-building margin-r-5"></i> {{ __('city') }}</strong> 
                                    <p class="text-muted">
                                        <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($item->city()->first())->name_ar }}" >{{ optional($item->city()->first())->name }}</a> 
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                
                                @if ($item->area()->first())
                                <li>
                                    <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('area') }}</strong> 
                                    <p class="text-muted">
                                        <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ optional($item->city()->first())->name_ar }}+{{ optional($item->area()->first())->name_ar }}" >{{ optional($item->area()->first())->name }}</a> 
                                    </p>
                                    <hr>
                                </li>
                                @endif 
                                
                                @if ($item->phone)
                                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                                <p class="text-muted">
                                    <a class="w3-text-blue" href="tel:{{ $item->phone }}" >{{ $item->phone }}</a>
                                </p>
                                <hr>
                                @endif
                            </ul>
                        </div>
                        
                        <div class="w3-col l9 m9 s12" > 
                           
                            <ul class="w3-ul" >
                                <li class="w3-large w3-padding cursor" onclick="$('.clinic-chart-list-item{{ $item->id }}').slideToggle(400)" >
                                    <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('reservations graph')  }}</b>
                                </li>
                                <li class="clinic-chart-list-item{{ $item->id }}" >
                                    @if (count($item->getChartData()) > 0) 
                            <canvas  id="chartDiv{{ $item->id }}" class="w3-block" style="width: 100%; height: 300px;"></canvas> 
                            @endif
                                </li>
                                <li class="w3-large w3-padding cursor" onclick="$('.clinic-order-list-item{{ $item->id }}').slideToggle(400)" >
                                    <b><i class="fa fa-angle-double-left" style="padding: 5px" ></i>{{__('last 10 reservations')  }}</b>
                                </li>
                                 @foreach($item->orders()->latest()->take(10)->get() as $order)
                                <li class="clinic-order-list-item{{ $item->id }} order-list-item clinic-order-item" >
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img 
                                                    class="media-object shadow w3-circle {{ App\helper\Helper::randColor() }}" 
                                                    style="width: 60px;height: 60px;padding: 3px" 
                                                    src="{{ optional($order->patient)->url }}" alt="...">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                {{ optional($order->patient)->name }}
                                            </h4>
                                            <table class="table" >
                                                <tr>
                                                    <td>{{ __('part') }}</td>
                                                    <td>{{ $order->part_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('reservation') }}</td>
                                                    <td>{{ $order->reservation_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('type') }}</td>
                                                    <td>{{ $order->getReservationTypeAr() }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('date') }}</td>
                                                    <td>{{ $order->date }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            
                        </div>
                        
                        
                    </div>
                </div> 
                @endforeach

                
                <div class="tab-pane" id="sendMessage">
                    <form class="form-horizontal form" method="post" action="{{ url('/notification/send-message') }}" >
                        @csrf
                        
                        <input type="hidden" name="user_id" value="{{ $doctor->id }}"  >
                        <input type="hidden" name="user_type" value="DOCTOR"  >
                        <input type="hidden" name="tokens[]" value="{{ $doctor->firebase_token }}"  > 
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">{{ __('title_ar') }}</label>  
                            <div class="col-sm-10">
                                <input type="text" name="title_ar" class="form-control" placeholder="{{ __('title_ar') }}" required="" >
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">{{ __('title_en') }}</label>  
                            <div class="col-sm-10">
                                <input type="text" name="title_en" class="form-control" placeholder="{{ __('title_en') }}" required="">
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <label for="inputExperience" class="col-sm-2 control-label">{{ __('message_ar') }}</label>

                            <div class="col-sm-10">
                                <textarea class="form-control" name="message_ar" id="inputExperience" required="" placeholder="{{ __('message_ar') }}"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputExperience" class="col-sm-2 control-label">{{ __('message_en') }}</label>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js" ></script>

@foreach($doctor->clinics()->get() as $item)
@if (count($item->getChartData()) > 0)
<script>
var ctx{{ $item->id }} = document.getElementById('chartDiv{{ $item->id }}').getContext('2d');
var myChart{{ $item->id }} = new Chart(ctx{{ $item->id }}, {
    type: 'line',
    data: {
        labels: [
          @foreach($item->getChartData() as $key => $value)
            '{{ $key }}', 
          @endforeach
        ],
        datasets: [{
            label: '{{ __("clinic reservations") }}',
            data: [
                @foreach($item->getChartData() as $key => $value)
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
@endforeach


@endsection
