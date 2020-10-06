@extends("layout.app", ["title" => $title])

 

@section("content")
<form role="form" method="post" class="form" action="{{ url('/') }}/{{ $route }}" autocomplete="off" id="form" enctype="multipart/form-data" >
    {{ csrf_field() }}
    <div class="box-body">
        @foreach($model->cols as $col => $array)
        @if (isset($array["editable"]))
        @if ($array["editable"])
        <div class="form-group">
            <label for="">{{ $array["ar"] }} *</label>
            <input type="text" class="form-control {{ $col }}" {{ $array['required']? 'required': '' }} name="{{ $col }}" placeholder='{{ $array["ar"] }}'  >
        </div>
        @endif
        @endif
        @endforeach
        @yield("fields")
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-flat">أضافة</button>
        <button type="button" class="btn btn-default btn-flat" onclick="showPage('{{ $backUrl | '' }}')" >رجوع</button>
    </div>
</form>
@endsection


<script>
    formAjax();
</script>
