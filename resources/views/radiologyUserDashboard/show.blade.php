@extends("layout.app", ['title' => 'حجز معمل الاشعه رقم' . $radiologyorder->id])
 

<?php
if ($radiologyorder->insurance_accept == 'accepted') {
    $label = "success";
    $text = "موافقه";
} else if ($radiologyorder->insurance_accept == 'required') {
    $label = "primary";
    $text = "مطلوب";
} else if ($radiologyorder->insurance_accept == 'refused') {
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
    @if ($radiologyorder->photo != null)
    <img class="w3-block w3-round" src="{{ url('/image/radiologyorder/') }}/{{ $radiologyorder->photo }}" height="200px" onclick="viewImage(this)"  >
    @endif
</center>
<br>
<table class="w3-table" >
    <tr>
        <td>
              المريض /
           {{ ($radiologyorder->patient()->first())? $radiologyorder->patient()->first()->name : '' }}
        </td>
        <td>
                شركة التامين
                
         / {{ ($radiologyorder->patient()->first()->insurance()->first())? $radiologyorder->patient()->first()->insurance()->first()->name : '' }}
        </td>
    </tr>
    <tr>
        <td>
             معمل الاشعه 
           / {{ ($radiologyorder->radiology()->first())? $radiologyorder->radiology()->first()->name : '' }}
        </td>
        <td>
            موافقة شركة التامين / {!! $html !!}
        </td>
    </tr>
    <tr>
        <td> 
        </td>
        <td>
            كود شركة التامين / {{ $radiologyorder->insurance_code }}
        </td>
    </tr>
</table>
<table class="table table-bordered" >
    <tr>
        <td>الكود</td>
        <td>اسم الاشعه</td>
    </tr>
    @foreach($radiologyorder->radiology_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ $detail->rays()->first()->name }}</td>
    </tr> 
    @endforeach
</table>
<br>
<br>
<a href="" class="btn btn-default btn-flat"   >رجوع</a>
@endsection