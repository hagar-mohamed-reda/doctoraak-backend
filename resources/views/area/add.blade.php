 

@php
    $model = new App\Area; 
    $route = "area/store";
    $title = "اضافة منطقه";
    $backUrl = 'area';
@endphp
 

@extends("layout.add", ["model" => $model, "route" => $route, "title" => $title, 'backUrl' => $backUrl])
 
@section("fields")  
<div class="form-group">
    <label for="">المدينه *</label>
    <select class="form-control select2" name="city_id" >
        @foreach(App\City::all() as $city)
        <option value="{{ $city->id }}" >{{ $city->name }}</option>
        @endforeach
    </select>
</div> 
@endsection


<script>
    $("select").select2(); 

</script>
