@extends("layout.app", ['title' => 'اضافة نوع دواء'])

@section("content") 
    
@php
    $model = new App\MedicineType; 
    $route = "medicinetype/store";
    $title = "اضافة نوع دواء";
    $backUrl = "medicinetype";
@endphp

{!! view("layout.add", compact("model", "route", "title", "backUrl")) !!}

@endsection


