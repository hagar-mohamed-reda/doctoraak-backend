
@php
if ($pharmacyorder->insurance_accept == 'accepted') {
    $label = "success";
    $text = "موافقه";
} else if ($pharmacyorder->insurance_accept == 'required') {
    $label = "primary";
    $text = "مطلوب";
} else if ($pharmacyorder->insurance_accept == 'refused') {
    $label = "danger";
    $text = "مرفوض";
} else {
    $label = "default";
    $text = "غير متاحه";
}

$html = "<span class='label label-$label' >$text</span>";

$counter = 1;
@endphp
 
<center>
    @if (file_exists(public_path('/image/pharmacyorder/') . "/" . $pharmacyorder->photo))
    <img class="w3-block w3-round" style="width: 50%" src="{{ url('/image/pharmacyorder/') }}/{{ $pharmacyorder->photo }}"  >
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
            {{ optional($pharmacyorder->patient)->name  }}
        </td>
    </tr>
    <tr>
        <th>
            {{ __('pharmacy') }} 
        </th>
        <td>
            {{ optional($pharmacyorder->pharmacy)->name }}
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
    <tr>
        <td>#</td>
        <td>{{ __('medicine') }}</td>
        <td>{{ __('medicine_type') }}</td> 
    </tr>
    @foreach($pharmacyorder->pharmacy_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ optional($detail->medicine)->name }}</td>
        <td>{{ optional($detail->medicine_type)->name | '' }}</td>
    </tr> 
    @endforeach
</table>
<br>
<br>  