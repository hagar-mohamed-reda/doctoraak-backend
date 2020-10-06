
@php
if ($laborder->insurance_accept == 'accepted') {
    $label = "success";
    $text = "موافقه";
} else if ($laborder->insurance_accept == 'required') {
    $label = "primary";
    $text = "مطلوب";
} else if ($laborder->insurance_accept == 'refused') {
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
    @if (file_exists(public_path('/image/laborder/') . "/" . $laborder->photo))
    <img class="w3-block w3-round" style="width: 50%" src="{{ url('/image/laborder/') }}/{{ $laborder->photo }}"  >
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
            {{ optional($laborder->patient)->name  }}
        </td>
    </tr>
    <tr>
        <th>
            {{ __('lab') }} 
        </th>
        <td>
            {{ optional($laborder->lab)->name }}
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
        <td>{{ __('analysis') }}</td> 
    </tr>
    @foreach($laborder->lab_order_details()->get() as $detail)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($detail->analysis)->name }}</td> 
    </tr> 
    @endforeach
</table>
<br>
<br>  