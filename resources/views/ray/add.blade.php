@extends("layout.app", ['title' => 'اضافة اشعه'])

@section("content") 
    
@php
    $model = new App\Ray; 
    $route = "ray/store";
    $title = "اضافة اشعه";
    $backUrl = "ray";
@endphp

{!! view("layout.add", compact("model", "route", "title", "backUrl")) !!}

@endsection


