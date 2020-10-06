@extends("layout.app", ['title' => 'اضافة تحليل'])

@section("content") 
    
@php
    $model = new App\Analysis; 
    $route = "analysis/store";
    $title = "اضافة تحليل";
    $backUrl = "analysis";
@endphp

{!! view("layout.add", compact("model", "route", "title", "backUrl")) !!}

@endsection


