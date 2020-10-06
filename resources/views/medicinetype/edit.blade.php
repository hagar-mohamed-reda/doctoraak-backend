@extends("layout.app", ['title' => 'اضافة نوع دواء'])

@section("content") 
    
@php
    $model = new App\MedicineType; 
    $route = "medicinetype/update/". $obj->id;
    $title = "تعديل نوع دواء";
    $backUrl = "medicinetype";
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", "backUrl")) !!}

@endsection
 