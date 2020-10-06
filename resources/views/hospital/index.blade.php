@extends("layout.app")


@section("title")
    الدكاتره
@endsection

@section("boxHeader")
    <a href="{{ url('/') }}/doctor/create" class="btn btn-primary btn-flat windowmac" >اضافة دكتور</a>
@endsection

@section("content")
@php 
$model = new App\Doctor;
@endphp

<table class="table" id="table" >
    <thead>
        <tr>
            @foreach($model->cols as $col => $array)
            <th>{{ $array["ar"] }}</th>
            @endforeach
            <th>التخصص</th>
            <th>الدرجه العلميه</th>
            <th>الصوره الشخصيه</th>
            <th>السيره الذاتيه</th>
            <th>تاريخ الانشاء</th>
            <th></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
@endsection

@section("scripts")
<script>
$(document).ready(function() {
     $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('doctorData') }}",
        "columns":[
            { "data": "id" },
            { "data": "name" }, 
            { "data": "name_ar" }, 
            { "data": "name_fr" }, 
            { "data": "phone" }, 
            { "data": "active" }, 
            { "data": "sms_code" },
            { "data": "email" },  
            { "data": "reservation_rate" }, 
            { "data": "degree_rate" }, 
            { "data": "specialization" }, 
            { "data": "degree" }, 
            { "data": "image" }, 
            { "data": "file" }, 
            { "data": "created_at" }, 
            { "data": "action" }
        ]
     });
});
</script>
@endsection
