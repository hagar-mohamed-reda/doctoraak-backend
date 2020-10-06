
@php
if ($radiologyorder->insurance_accept == 'accepted') {
    $radiologyel = "success";
    $text = "موافقه";
} else if ($radiologyorder->insurance_accept == 'required') {
    $radiologyel = "primary";
    $text = "مطلوب";
} else if ($radiologyorder->insurance_accept == 'refused') {
    $radiologyel = "danger";
    $text = "مرفوض";
} else {
    $radiologyel = "default";
    $text = "غير متاحه";
}

$html = "<span class='radiologyel radiologyel-$radiologyel' >$text</span>";

$counter = 1;
@endphp
 
<center>
    @if (file_exists(public_path('/image/radiologyorder/') . "/" . $radiologyorder->photo))
    <img class="w3-block w3-round" style="width: 50%" src="{{ url('/image/radiologyorder/') }}/{{ $radiologyorder->photo }}"  >
    @endif
</center>
<br>
<div class="w3-row" >
    <table class="w3-table w3-col l6 m6 s12" >
    <tr>
        <th>
            {{ __('patient') }}
        </th> 
        <td>
            {{ optional($radiologyorder->patient)->name  }}
        </td>
    </tr>
    <tr>
        <th>
            {{ __('radiology') }} 
        </th>
        <td>
            {{ optional($radiologyorder->radiology)->name }}
        </td> 
    </tr>
    <tr>
        <th> 
            {{ __('insurance_accept') }}
        </th> 
        <td>
            {!! $html !!}
        </td>
    </tr>
</table>
</div>
<table class="table table-bordered" >
    <tr class="w3-dark-gray" >
        <td>#</td>
        <td>{{ __('x-ray') }}</td> 
    </tr>
    @foreach($radiologyorder->radiology_order_details()->get() as $detail)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($detail->rays)->name }}</td> 
    </tr> 
    @endforeach
</table>
<br>
<br>  