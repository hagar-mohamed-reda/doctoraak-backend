<div style="width: 300px" >
    
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/patient/edit') . '/' . $patient->id }}')" ></i>
 
    <i class="fa fa-desktop w3-text-green w3-button" onclick="showPage('patient/show/' + '{{ $patient->id }}')" ></i>
   
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/patient/remove/') .'/' . $patient->id }}')" ></i>
    
    @if ($patient->api_token)
    <i class="fa fa-book w3-text-purple w3-button"
        data-toggle="modal"
        data-target="#orderModal{{ $patient->id }}" > {{ __('book') }} </i>
    
    @endif
    
</div> 

<!-- add modal -->
<div class="modal fade"   role="dialog" id="orderModal{{ $patient->id }}" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-sm" role="document" >
        <div class="modal-content w3-light-gray">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('orders') }}</center>
            </div>
            <div class="modal-body">
                <ul class="w3-ul" >
                    
                    <li class="w3-padding cursor"  onclick="showPage('clinicorder/create?api_token={{ $patient->api_token }}&patient_id=' + '{{ $patient->id }}')"  >
                        <div class="w3-round w3-white w3-border-gray w3-border" >
                            <img src="{{ url('/image/icon/doctor.png') }}" height="40px" class="shadow w3-padding w3-round w3-blue"  >
                            <b class="w3-padding" >{{ __('book a reservation') }}</b>
                        </div>
                    </li>
                    
                    <li class="w3-padding cursor"  onclick="showPage('laborder/create?api_token={{ $patient->api_token }}&patient_id=' + '{{ $patient->id }}')"  >
                        <div class="w3-round w3-white w3-border-gray w3-border" >
                            <img src="{{ url('/image/icon/lab.png') }}" height="40px" class="shadow w3-padding w3-round w3-red"  >
                            <b class="w3-padding" >{{ __('book for lab') }}</b>
                        </div>
                    </li>
                    
                    <li class="w3-padding cursor"  onclick="showPage('radiologyorder/create?api_token={{ $patient->api_token }}&patient_id=' + '{{ $patient->id }}')"  >
                        <div class="w3-round w3-white w3-border-gray w3-border" >
                            <img src="{{ url('/image/icon/x-ray.png') }}" height="40px" class="shadow w3-padding w3-round w3-cyan"  >
                            <b class="w3-padding" >{{ __('book for radiology') }}</b>
                        </div>
                    </li>
                     
                    
                </ul>
                
            </div>
            <div class="modal-footer" >
                <center>
                    <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Close">{{ __('close') }}</button>
                </center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="dropdown hidden">
    @if ($patient->active == 1)
    <a href="#" class="w3-text-deep-orange w3-button"  onclick="sendUrl('{{ url('/') }}/patient/deactive/{{ $patient->id }}', null, this)"  >
        <i class="fa fa-info-circle" ></i> <span>الغاء التفعيل</span>
    </a>
    @else
    <a href="#" onclick="sendUrl('{{ url('/') }}/patient/active/{{ $patient->id }}', null, this)"  class="w3-text-green w3-button"   >
        <i class="fa fa-info-circle" ></i> <span>تفعيل</span>
    </a>
    @endif 
    
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="fa fa-bars"></span>
  </button>
  <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenu1">
      <li>
          <a href="#" onclick="edit('{{ url('/patient/edit') . '/' . $patient->id }}')" >
              <i class="fa fa-desktop" style="margin: 5px" ></i>
              {{ __('show') }}
          </a>
      </li> 
      @if ($patient->active == 1)
      <li>
          <a href="#" onclick="sendUrl('{{ url('/') }}/patient/deactive/{{ $patient->id }}', null, this)" >
              <i class="fa fa-info-circle" style="margin: 5px" ></i>
              {{ __('deactive') }}
          </a>
      </li> 
      @else
      <li>
          <a href="#" onclick="sendUrl('{{ url('/') }}/patient/deactive/{{ $patient->id }}', null, this)" >
              <i class="fa fa-info-circle" style="margin: 5px" ></i>
              {{ __('active') }}
          </a>
      </li> 
      @endif 
      
      <li>
          <a href="#" onclick="edit('{{ url('/patient/edit') . '/' . $patient->id }}')" >
              <i class="fa fa-desktop" style="margin: 5px" ></i>
              {{ __('show') }}
          </a>
      </li> 
      <li>
          <a href="#" onclick="edit('{{ url('/patient/edit') . '/' . $patient->id }}')" >
              <i class="fa fa-desktop" style="margin: 5px" ></i>
              {{ __('show') }}
          </a>
      </li> 
    <li><a href="#">Separated link</a></li>
  </ul>
</div>