<div style="width:250px" class="hidden" >
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/lab/edit') . '/' . $lab->id }}')" ></i>
    
    <i class="fa fa-calendar w3-text-indigo w3-button" onclick="edit('{{ url('/lab/working_hours/edit') . '/' . $lab->id }}', 'workingHourModal', 'workingHourModalPlace')" > {{ __('working hours') }} </i>
 
    <i class="fa fa-desktop w3-text-green w3-button" onclick="showPage('lab/show/' + '{{ $lab->id }}')" ></i>
   
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/lab/remove/') .'/' . $lab->id }}')" ></i>     
</div> 

<div class="dropdown">
    <button class="btn w3-white dropdown-toggle w3-circle shadow" style="width: 15px!important;height: 25px!important;" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-option-vertical"></span>
  </button>
  <ul class="dropdown-menu shadow w3-border-0 text-center" aria-labelledby="dropdownMenu1">
    <li onclick="edit('{{ url('/lab/edit') . '/' . $lab->id }}')" > 
        <a href="#"><i class="fa fa-edit" style="padding: 5px" ></i>{{ __('edit') }}</a>
    </li>
    <li onclick="showPage('lab/show/' + '{{ $lab->id }}')" ><a href="#"><i class="fa fa-desktop" style="padding: 5px" ></i>{{ __('show') }}</a></li>
    <li onclick="remove('', '{{ url('/lab/remove/') .'/' . $lab->id }}')" ><a href="#"><i class="fa fa-trash" style="padding: 5px" ></i>{{ __('remove') }}</a></li>
    <li role="separator" class="divider"></li>
    <li onclick="edit('{{ url('/lab/working_hours/edit') . '/' . $lab->id }}', 'workingHourModal', 'workingHourModalPlace')" ><a href="#"><i class="fa fa-calendar" style="padding: 5px" ></i>{{ __('working hours') }}</a></li>
  </ul>
</div>