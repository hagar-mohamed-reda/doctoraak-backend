  
    
@php
    $model = new App\Area; 
    $route = "area/update/". $obj->id;
    $title = "تعديل منطقه";
    $backUrl = 'area';
@endphp

@extends("layout.edit", ["obj" => $obj, "model" => $model, "route" => $route, "title" => $title, "backUrl" => 'area'])
 
@section("fields")  
<div class="form-group">
    <label for="">المدينه *</label>
    <select class="form-control select2 city" name="city_id" >
        @foreach(App\City::all() as $city)
        <option value="{{ $city->id }}" >{{ $city->name }}</option>
        @endforeach
    </select>
</div> 
@endsection

<script>
    $("select").select2();
    
    
    $('.city').val('{{ $obj->city_id }}');

</script>