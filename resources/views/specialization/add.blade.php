@php
$model = new App\Specialization; 
$route = "specialization/store";
$title = "اضافة تخصص"; 
$backUrl = 'specialization';
@endphp

@extends("layout.add", ["model" => $model, "route" => $route, "title" => $title, 'backUrl' => $backUrl])
 
@section("fields")  
<div class="form-group">
    <label for="">الايقونه *</label>
    <div class="form-control cursor" onclick="$('.image').click()" >
        <span class="fa fa-image w3-large" ></span>
        <span>من فضلك قم برفع صوره ذات ابعاد صغيره يفضل 90&times;90 </span>
    </div>
    <br>
    <img class="imageView w3-round" width="50px" height="50px" onclick="viewImage(this)" >
    <input type="file" onchange="loadImage(this, event)" class="hidden form-control image" required="" name="image" accept="image/x-png,image/gif,image/jpeg" >
</div> 
@endsection
