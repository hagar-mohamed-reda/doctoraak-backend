@php
$model = new App\Doctor; 
$route = "doctor/update/" . $obj->id;
$title = "تعديل دكتور"; 
@endphp

@extends("layout.edit", ["obj" => $obj, "model" => $model, "route" => $route, "title" => $title])

@php
    $specials = App\Specialization::all();
    $degrees = App\Degree::all();
@endphp

@section("fields")  

<div class="form-group" >
    <label for="">البريد الالكترونى *</label>
    <input type="email" class="form-control" name="email" value="{{ $obj->email }}" >
</div>
<div class="form-group" >
    <label for="">كلمة المرور *</label>
    <input type="password" class="form-control" name="password" value="{{ $obj->password }}" >
</div>
<div class="form-group">
    <label for="">التخصص *</label>
    <select class="form-control select2 specialization" name="specialization" >
        <option disabled="" >قم باختيار التخصص</option>
        @foreach($specials as $special)
        <option value="{{ $special->id }}" >{{ $special->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="">الدرجه العلميه *</label>
    <select class="form-control select2 degree" name="degree" >
        <option disabled="" >قم باختيار الدرجه العلميه</option>
        @foreach($degrees as $degree)
        <option value="{{ $degree->id }}" >{{ $degree->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <span>التفعيل</span>
    <input type="checkbox" name="active" class="shadow" id="switch" placeholder="" {{ $obj->active==1?"checked": '' }} >
    <label for="switch" class="switch" ></label>
</div>
<div class="form-group">
    <label for="">الصوره الشخصيه *</label>
    <div class="form-control cursor" onclick="$('.image').click()" >
        <span class="fa fa-image w3-large" ></span> 
        <span>من فضلك قم باختيار ملفات الصور </span>
    </div>
    <br>
    <img 
        class="imageView w3-round" 
        width="50px" height="50px" 
        src="{{ url('/image/doctor') }}/{{ $obj->photo }}"
        onclick="viewImage(this)" >
    <input type="file" onchange="loadImage(this, event)" class="hidden form-control image"  name="image" accept="image/x-png,image/gif,image/jpeg" >
</div> 
<div class="form-group">
    <label for=""> السيره الذاتيه *</label>
    <div class="form-control cursor" onclick="$('.cv').click()" >
        <span class="fa fa-file-pdf-o w3-large" ></span>
        <span>من فضلك قم باختيار ملفات pdf</span>
    </div>
    <br>
    <span class="fileView label label-info" data-src="{{ url('/file/doctor') }}/{{ $obj->cv }}" onclick="viewFile(this)" >{{ $obj->cv }}</span>
    <input type="file" onchange="loadFile(this, event)" class="hidden form-control cv"  name="cv" accept="application/pdf" >
</div>
<div class="form-group" id="reservation_rate" >
    <label for=""> تقيم الحجزات *</label>
    <input type="hidden" id="reservation_rate_value" name="reservation_rate" >
</div>
<div class="form-group" id="degree_rate" >
    <label for="">تقيم الدرجه العلميه *</label>
    <input type="hidden" id="degree_rate_value" name="degree_rate" >
</div>
@endsection


@section("scripts")
<script>
    $("select").select2();
    
    var r1 = new Ratebar(document.getElementById("reservation_rate"));
    r1.setOnRate(function(){
        document.getElementById("reservation_rate_value").value = r1.value;
    });
    
    var r2 = new Ratebar(document.getElementById("degree_rate"));
    r2.setOnRate(function(){
        document.getElementById("degree_rate_value").value = r2.value;
    });
    
    
    // set values
    $(".degree").val('{{ $obj->degree_id }}');
    $(".specialization").val('{{ $obj->specialization_id }}');
    
    r1.rate('{{ $obj->reservation_rate }}');
    r2.rate('{{ $obj->degree_rate }}');
</script>
@endsection
