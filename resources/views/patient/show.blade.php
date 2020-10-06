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
      <li><a href="#" onclick="showPage('patient')" >{{ __('patients') }}</a></li>
      <li class="active">{{ $patient->name }}</li>
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
                    width="100px" style="height: 100px" src="{{ $patient->url }}" alt="User profile picture">

                <h3 class="profile-username text-center">{{ $patient->name }}</h3>

                <p class="text-muted text-center">{{ __($patient->gender) }}</p>

                <ul class="list-group list-group-unbordered">
                    
                    @if ($patient->orders()->count() > 0)
                    <li class="list-group-item">
                        <b>{{ __('reservations') }}</b> <a class="pull-right">{{ number_format($patient->orders()->count()) }}</a>
                    </li>
                    @endif
                    
                    @if ($patient->lab_orders()->count() > 0)
                    <li class="list-group-item">
                        <b>{{ __('lab orders') }}</b> <a class="pull-right">{{ number_format($patient->lab_orders()->count()) }}</a>
                    </li>
                    @endif
                    
                    @if ($patient->radiology_orders()->count() > 0)
                    <li class="list-group-item">
                        <b>{{ __('radiology orders') }}</b> <a class="pull-right">{{ number_format($patient->radiology_orders()->count()) }}</a>
                    </li>
                    @endif
                    
                    @if ($patient->pharmacy_orders()->count() > 0)
                    <li class="list-group-item">
                        <b>{{ __('pharmacy orders') }}</b> <a class="pull-right">{{ number_format($patient->pharmacy_orders()->count()) }}</a>
                    </li> 
                    @endif
                    
                    @if ($patient->block_days > 0)
                    <li class="list-group-item">
                        <b>{{ __('block days') }}</b> <a class="pull-right">{{ number_format($patient->block_days) }}</a>
                    </li> 
                    @endif
                    
                    @if ($patient->block_date)
                    <li class="list-group-item">
                        <b>{{ __('block date') }}</b> <a class="pull-right">{{ $patient->block_date }}</a>
                    </li>
                    @endif 
                </ul>

                <a href="#" class="btn btn-primary btn-block fa fa-print" onclick="window.print()"  > <b> {{ __('print') }} </b> </a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('About Me') }}</h3>
            </div>        
            <!-- /.box-header -->
            <div class="box-body"> 
                @if ($patient->name_ar)
                <strong><i class="fa fa-user-circle margin-r-5"></i> {{ __('name_ar') }}</strong> 
                <p class="text-muted">
                    {{ $patient->name_ar }}
                </p>
                <hr>
                @endif
                
                @if ($patient->name_fr)
                <strong><i class="fa fa-user-circle margin-r-5"></i> {{ __('name_fr') }}</strong> 
                <p class="text-muted">
                    {{ $patient->name_fr }}
                </p>
                <hr>
                @endif
                
                @if ($patient->email)
                <strong><i class="fa fa-envelope margin-r-5"></i> {{ __('email') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="mailto:{{ $patient->email }}" >{{ $patient->email }}</a>
                </p>
                <hr>
                @endif
                
                @if ($patient->phone)
                <strong><i class="fa fa-phone-square margin-r-5"></i> {{ __('phone') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" href="tel:{{ $patient->phone }}" >{{ $patient->phone }}</a>
                </p>
                <hr>
                @endif
                
                @if ($patient->birthdate)
                <strong><i class="fa fa-birthday-cake margin-r-5"></i> {{ __('birthdate') }}</strong> 
                <p class="text-muted">
                    {{ $patient->birthdate }}
                </p>
                <hr>
                @endif
                
                @if ($patient->insurance)
                <strong><i class="fa fa-building margin-r-5"></i> {{ __('insurance') }}</strong> 
                <p class="text-muted">
                    {{ optional($patient->insurance)->name }}
                </p>
                <hr>
                @endif
                
                @if ($patient->insurance_code_id)
                <strong><i class="fa fa-barcode margin-r-5"></i> {{ __('insurance_code_id') }}</strong> 
                <p class="text-muted">
                    {{ $patient->insurance_code_id }}
                </p>
                <hr>
                @endif
                 
                @if ($patient->address)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('address') }}</strong> 
                <p class="text-muted"> 
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ $patient->address }}" >{{ $patient->address }}</a>
                </p>
                <hr>
                @endif
                
                @if ($patient->address_ar)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('address_ar') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ $patient->address_ar }}" >{{ $patient->address_ar }}</a>
                </p>
                <hr>
                @endif
                
                @if ($patient->address_fr)
                <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('address_fr') }}</strong> 
                <p class="text-muted">
                    <a class="w3-text-blue" target="_blank" href="https://www.google.com/maps/place/{{ $patient->address_fr }}" >{{ $patient->address_fr }}</a>
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
                <li class="active"><a href="#orderTab" data-toggle="tab">{{ __('orders') }}</a></li>
                <li><a href="#sendMessage" data-toggle="tab">{{ __('send message to patient') }}</a></li> 
            </ul>
            <div class="tab-content">
                
                <div class="active tab-pane" id="orderTab">
                    <center>
                        <div class="chip shadow-0 w3-button" onclick="$('.order-list-item').hide();$('.clinic-order-item').slideDown(200);" >
                            <img src="{{ url('/image/icon/doctor.png') }}" class="w3-indigo" style="padding: 3px" alt="Person" width="20" height="20">
                            {{ __('reservations') }}
                        </div>
                        <div class="chip shadow-0 w3-button" onclick="$('.order-list-item').hide();$('.lab-order-item').slideDown(200);" >
                            <img src="{{ url('/image/icon/lab.png') }}" class="w3-red" style="padding: 3px" alt="Person" width="20" height="20">
                            {{ __('lab orders') }}
                        </div>
                        <div class="chip shadow-0 w3-button" onclick="$('.order-list-item').hide();$('.radiology-order-item').slideDown(200);" >
                            <img src="{{ url('/image/icon/x-ray.png') }}" class="w3-purple" style="padding: 3px" alt="Person" width="20" height="20">
                            {{ __('radiology orders') }}
                        </div>
                        <div class="chip shadow-0 w3-button" onclick="$('.order-list-item').hide();$('.pharmacy-order-item').slideDown(200);" >
                            <img src="{{ url('/image/icon/medicine.png') }}" class="w3-teal" style="padding: 3px" alt="Person" width="20" height="20">
                            {{ __('pharmacy orders') }}
                        </div>
                    </center>
                    <br>
                    <ul class="w3-ul" >
                        <li class="order-list-item clinic-order-item" >
                            <div class="text-center w3-large w3-dark-gray w3-round" > 
                                {{ __('reservations') }}
                            </div>
                        </li>
                        @foreach($patient->orders()->latest()->take(10)->get() as $item)
                        <li class="order-list-item clinic-order-item" >
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img 
                                            class="media-object shadow w3-circle {{ App\helper\Helper::randColor() }}" 
                                            style="width: 60px;height: 60px;padding: 3px" src="{{ url('/image/icon/doctor.png') }}" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{ optional(optional($item->clinic)->doctor)->name }}
                                    </h4>
                                    <table class="table" >
                                        <tr>
                                            <td>{{ __('part') }}</td>
                                            <td>{{ $item->part_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('reservation') }}</td>
                                            <td>{{ $item->reservation_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('type') }}</td>
                                            <td>{{ $item->getReservationTypeAr() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('date') }}</td>
                                            <td>{{ $item->date }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        
                        <li class="order-list-item lab-order-item" >
                            <div class="text-center w3-large w3-dark-gray w3-round" > 
                                {{ __('lab orders') }}
                            </div>
                        </li>
                        @foreach($patient->lab_orders()->latest()->take(10)->get() as $item)
                        <li class="order-list-item lab-order-item" >
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img 
                                            class="media-object shadow w3-circle {{ App\helper\Helper::randColor() }}" 
                                            style="width: 60px;height: 60px;padding: 3px" src="{{ url('/image/icon/lab.png') }}" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{ optional($item->lab)->name }}
                                    </h4>
                                    <table class="table" >
                                        <tr>
                                            <td>{{ __('accept') }}</td>
                                            <td>
                                                @if ($item->accept)
                                                <i class="fa fa-check w3-text-green" ></i>
                                                @else
                                                <i class="fa fa-close w3-text-red" ></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('insurance_accept') }}</td>
                                            <td>{{ __($item->insurance_accept) }}</td>
                                        </tr> 
                                        <tr>
                                            <td>{{ __('date') }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('notes') }}</td>
                                            <td>{{ $item->notes }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        
                        
                        <li class="order-list-item radiology-order-item" >
                            <div class="text-center w3-large w3-dark-gray w3-round" > 
                                {{ __('radiology orders') }}
                            </div>
                        </li>
                        @foreach($patient->radiology_orders()->latest()->take(10)->get() as $item)
                        <li class="order-list-item radiology-order-item" >
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img 
                                            class="media-object shadow w3-circle {{ App\helper\Helper::randColor() }}" 
                                            style="width: 60px;height: 60px;padding: 3px" src="{{ url('/image/icon/x-ray.png') }}" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{ optional($item->radiology)->name }}
                                    </h4>
                                    <table class="table" >
                                        <tr>
                                            <td>{{ __('accept') }}</td>
                                            <td>
                                                @if ($item->accept)
                                                <i class="fa fa-check w3-text-green" ></i>
                                                @else
                                                <i class="fa fa-close w3-text-red" ></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('insurance_accept') }}</td>
                                            <td>{{ __($item->insurance_accept) }}</td>
                                        </tr> 
                                        <tr>
                                            <td>{{ __('date') }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('notes') }}</td>
                                            <td>{{ $item->notes }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        
                        
                        <li class="order-list-item pharmacy-order-item" >
                            <div class="text-center w3-large w3-dark-gray w3-round" > 
                                {{ __('pharmacy orders') }}
                            </div>
                        </li>
                        @foreach($patient->pharmacy_orders()->latest()->take(10)->get() as $item)
                        <li class="order-list-item pharmacy-order-item" >
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img 
                                            class="media-object shadow w3-circle {{ App\helper\Helper::randColor() }}" 
                                            style="width: 60px;height: 60px;padding: 3px" src="{{ url('/image/icon/medicine.png') }}" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{ optional($item->radiology)->name }}
                                    </h4>
                                    <table class="table" >
                                        <tr>
                                            <td>{{ __('accept') }}</td>
                                            <td>
                                                @if ($item->accept)
                                                <i class="fa fa-check w3-text-green" ></i>
                                                @else
                                                <i class="fa fa-close w3-text-red" ></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('insurance_accept') }}</td>
                                            <td>{{ __($item->insurance_accept) }}</td>
                                        </tr> 
                                        <tr>
                                            <td>{{ __('date') }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('notes') }}</td>
                                            <td>{{ $item->notes }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.tab-pane -->
                
                
                <div class="tab-pane" id="sendMessage">
                    <form class="form-horizontal form" method="post" action="{{ url('/notification/send-message') }}" >
                        @csrf
                        
                        <input type="hidden" name="user_id" value="{{ $patient->id }}"  >
                        <input type="hidden" name="user_type" value="PATIENT"  >
                        <input type="hidden" name="tokens[]" value="{{ $patient->firebase_token }}"  > 
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
<script>
     
     formAjax();
</script>
@endsection
