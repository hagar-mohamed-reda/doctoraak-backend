@extends("layout.app", ['title' => 'اضافة درجه علميه'])

@section("content") 
    
@php
    $model = new App\Degree; 
    $route = "degree/store";
    $title = "اضافة درجه علميه";
    $backUrl = 'degree';
@endphp

{!! view("layout.add", compact("model", "route", "title", 'backUrl')) !!}

@endsection


