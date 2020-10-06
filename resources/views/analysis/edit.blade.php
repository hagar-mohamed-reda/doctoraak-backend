@extends("layout.app", ['title' => 'اضافة تحليل'])

@section("content") 
    
@php
    $model = new App\Analysis; 
    $route = "analysis/update/". $obj->id;
    $title = "تعديل التحليل";
    $backUrl = "analysis";
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", "backUrl")) !!}

@endsection
 