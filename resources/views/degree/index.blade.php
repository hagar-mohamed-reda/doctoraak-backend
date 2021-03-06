@extends("layout.app", ["title" => " الدرجه العلميه"])



@section("boxHeader")
<button class="btn btn-primary btn-flat btn-sm" onclick="toggleCol(table, hiddenFields)" >عرض مختصر البيانات</button> 
<a href="#" onclick="showPage('{{ url('/') }}/degree/create')" class="btn btn-primary btn-flat  btn-sm" >اضافة درجه علميه</a>
@endsection

@section("content")
@php 
$model = new App\Specialization;
@endphp

<table class="table" id="table" >
    <thead>
        <tr>
            @foreach($model->cols as $col => $array)
            <th>{{ $array["ar"] }}</th>
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
@endsection

@section("scripts")
<script>
    var table = null;
    var hiddenFields= [];
    $(document).ready(function () {
        table =  $('#table').DataTable({ 
        "order": [[ 0, "desc" ]],
            "pageLength": 5,
            "autoWidth": false,
        "serverSide": true,
        "ajax": "{{ route('degreeData') }}",
        "columns":[
            { "data": "id" },
            { "data": "name" }, 
            { "data": "name_ar" }, 
            { "data": "name_fr" }, 
            { "data": "action" }
        ]
     });

        // index of columns will be hidden
        hiddenFields = [
            2,3
        ];

        // colapse columns
        toggleCol(table, hiddenFields);

    }); 

</script>
@endsection

