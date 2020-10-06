@extends("layout.app")

@section("content") 
    
@php
    $model = new App\Medicine; 
    $route = "medicine/update/". $obj->id;
    $title = "تعديل دواء";
    $backUrl = 'medicine';
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", "backUrl")) !!}

@endsection
 