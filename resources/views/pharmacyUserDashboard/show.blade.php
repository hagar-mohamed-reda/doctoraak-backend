@extends("layout.app", ['title' => 'طلب الصيدليه رقم' .  $pharmacyorder->id])

<?php
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
?>

@section("content")
<center>
    @if ($pharmacyorder->photo != null)
    <img class="w3-block w3-round" height="200px" src="{{ url('/image/pharmacyorder/') }}/{{ $pharmacyorder->photo }}"  >
    @endif
</center>
<br>
<table class="w3-table" >
    <tr>
        <td>
            المريض / 
            {{ ($pharmacyorder->patient)? $pharmacyorder->patient->name : '' }}
        </td>
        <td>
            شركة التامين / 
            {{ ($pharmacyorder->patient->insurance)? $pharmacyorder->patient->insurance->name : '' }}
        </td>
    </tr>
    <tr>
        <td>
            الصيدليه التى وافقت على الطلب
            / {{ ($pharmacyorder->pharmacy)? $pharmacyorder->pharmacy->name : '' }}
        </td>
        <td>
            موافقة شركة التامين / {!! $html !!}
        </td>
    </tr>
    <tr>
        <td> 
        </td>
        <td>
            كود شركة التامين / {{ $pharmacyorder->insurance_code }}
        </td>
    </tr>
</table>
<table class="table table-bordered" >
    <tr>
        <td>الكود</td>
        <td>اسم الدواء</td>
        <td>نوع الدواء</td>
    </tr>
    @foreach($pharmacyorder->pharmacy_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ $detail->medicine->name | '' }}</td>
        <td>{{ $detail->medicine_type->name | '' }}</td>
    </tr> 
    @endforeach
</table>
<br>
<br>
<a href="" class="btn btn-default btn-flat"  >رجوع</a>
@endsection