@extends("layout.app")

@section("content") 
    
@php
    $model = new App\Medicine; 
    $route = "medicine/store";
    $title = "اضافة دواء";
    $backUrl = 'medicine';
@endphp

{!! view("layout.add", compact("model", "route", "title", "backUrl")) !!}

@endsection


