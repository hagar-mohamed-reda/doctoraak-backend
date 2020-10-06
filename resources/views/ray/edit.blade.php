@extends("layout.app", ['title' => 'تعديل اشعه'])

@section("content") 
    
@php
    $model = new App\Ray; 
    $route = "ray/update/". $obj->id;
    $title = "تعديل اشعه";
    $backUrl = "ray";
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", "backUrl")) !!}

@endsection
 