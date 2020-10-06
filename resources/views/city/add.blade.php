@extends("layout.app", ['title' => 'اضافة مدينه'])

@section("content") 
    
@php
    $model = new App\City; 
    $route = "city/store";
    $title = "اضافة مدينه";
    $backUrl = 'city';
@endphp

{!! view("layout.add", compact("model", "route", "title", "backUrl")) !!}

@endsection


