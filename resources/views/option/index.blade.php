@extends("layout.app", ["title" => "ارسال رساله"])



@section("boxHeader")  
@endsection

@section("content")
 <form role="form" method="post" class="form" action="setting/send-message" autocomplete="off" id="form" enctype="multipart/form-data" >
    {{ csrf_field() }}
    <div class="box-body">
        <div class="form-group">
            <label for="">العنوان بالعربيه*</label>
            <input type="text" class="form-control"  name="title_ar" placeholder="العنوان بالعربيه" required="">
        </div>
        <div class="form-group">
            <label for="">العنوان بالانجليزيه*</label>
            <input type="text" class="form-control"  name="title_en" placeholder="العنوان بالانجليزيه" required="">
        </div>
        <div class="form-group">
            <label for="">الرساله بالعربيه*</label>
            <input type="text" class="form-control"  name="message_ar" placeholder="الرساله بالعربيه" required="">
        </div>
        <div class="form-group">
            <label for="">الرساله بالانجليزيه*</label>
            <input type="text" class="form-control"  name="message_en" placeholder="الرساله بالانجليزيه" required="">
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-flat">ارسال</button> 
    </div>
</form>
@endsection

@section("scripts")
<script> 
    formAjax();
</script>
@endsection
