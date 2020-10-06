@extends("layout.app", ['title' => 'تعديل الدرجه العميه'])

@section("content") 
    
@php
    $model = new App\Degree; 
    $route = "degree/update/". $obj->id;
    $title = "تعديل الدرجه العميه";
    $backUrl = 'degree';
@endphp

{!! view("layout.edit", compact("obj", "model", "route", "title", 'backUrl')) !!}

@endsection
 