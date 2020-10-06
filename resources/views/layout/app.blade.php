 

@yield("styles")

@yield('breadcrumb')

<!-- Main content -->
<section class="content ">

    @yield("out")

    <div class="row">
        <div class="col-xs-12">
            <!-- /.box -->
 
            <div class="box shadow w3-round nicescroll" style="border: 0px;height: auto!important" >
                <div class="box-header" style="height: auto" >
                    @yield("boxHeader")
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="tableContainer" >
                    @yield("content")
                </div>
                <!-- /.box-body -->
            </div> 
            <br>
            <br>
            <br>

            @yield("content2")
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@yield("section") 

<!-- include js files -->
@yield("scripts")

<script>
    formAjax(); 
    
    $(document).mousemove(function(){
         $("tr").each(function(){
            this.onclick = function(){
                //$(this).find('.dropdown-toggle').click();
                $('.dropdown-menu').slideUp(400);
                $(this).find('.dropdown-menu').slideDown(400);
                //$(this).find('.dropdown-toggle').dropdown('toggle');
            };
        }); 
        
        writeNames();
    });
    
    function writeNames() {
        $("input[name=name]").each(function(){
            var form = this.form;
            this.onkeyup = function(){
                $(form).find("input[name=name_ar]").val(this.value);
                $(form).find("input[name=name_fr]").val(this.value);
            };
        });
    }
</script>
