@extends("layout.app", ['title' => 'حجز معمل التحاليل رقم' . $laborder->id ])
 
<?php
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
?>

@section("content")
<center>
    @if ($laborder->photo != null)
    <img class="imageView w3-block w3-round" src="{{ url('/image/laborder/') }}/{{ $laborder->photo }}" onclick="viewImge(this)"  height="200px" >
    @endif
</center>
<br>
<table class="w3-table" >
    <tr>
        <td>
            المريض / {{ $laborder->patient->name }}
        </td>
        <td>
            شركة التامين
             / {{ ($laborder->patient()->first()->insurance()->first())? $laborder->patient()->first()->insurance()->first()->name : '' }}
        </td>
    </tr>
    <tr>
        <td>
            معمل التحاليل / {{ $laborder->lab()->first()->name }}
        </td>
        <td>
            موافقة شركة التامين / {!! $html !!}
        </td>
    </tr>
    <tr>
        <td> 
        </td>
        <td>
            كود شركة التامين / {{ $laborder->insurance_code }}
        </td>
    </tr>
</table>
<table class="table table-bordered" >
    <tr>
        <td>الكود</td>
        <td>اسم التحليل</td>
    </tr>
    @foreach($laborder->lab_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ $detail->analysis()->first()->name }}</td>
    </tr> 
    @endforeach
</table>
<br>
<br>
<a href="" class="btn btn-default btn-flat"  >رجوع</a>
@endsection