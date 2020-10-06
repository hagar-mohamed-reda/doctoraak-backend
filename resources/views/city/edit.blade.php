@extends("layout.app", ['title' => 'تعديل مدينه'])

@section("content") 
    
@php
    $model = new App\City; 
    $route = "city/update/". $obj->id;
    $title = "تعديل مدينه";
    $backUrl = 'city';
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", 'backUrl')) !!}

@endsection
 