<?php
if ($labOrder->insurance_accept == 'accepted') {
    $label = "success";
    $text = "موافقه";
} else if ($labOrder->insurance_accept == 'refused') {
    $label = "danger";
    $text = "مرفوض";
} else if ($labOrder->insurance_accept == 'required') {
    $label = "warning";
    $text = "مطلوب";
} else {
    $label = "default";
    $text = "غير متاحه";
}

$html = "<span class='label label-$label' >$text</span>";

$counter = 1;
?>


<!doctype html>
<html lang="">
    <head>



        {!! view("layout.css") !!}

        {!! view("template.commen") !!}


        <script>
            var public_path = '{{ url("/") }}';
        </script>
        <style> 
            .content-wrapper {
                overflow: hidden;
            } 
        </style>

        <style>
            table {
                direction: rtl;
            }

            .content-wrapper {
                direction: rtl;
                overflow: auto!important;
            }

            .breadcrumb {
                left: 10px!important;
                right: auto!important;
            }

            .content-wrapper {
                max-height: 664px!important;
            }
            .fixed .content-wrapper, .fixed .right-side {
                padding-top: 35px!important;
            }
        </style>
        {!! view("template.commen") !!}
    </head>

    <body class="hold-transition skin-blue-light fixed sidebar-mini"  >

        @php  
        $title = "حجز معمل التحاليل رقم" . $labOrder->id;
        @endphp  
 
        <div class="content-wrapper" style="margin: 0px"  >  
            @include("layout.broadcamb", compact("title"))

            <!-- Main content -->
            <section class="content  frame">


                <div class="row">
                    <div class="col-xs-12">
                        <!-- /.box -->

                        <div class="box shadow w3-round nicescroll" style="border: 0px" >
                            <div class="box-header">
                                 حجزات معامل التحليل
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive" id="tableContainer" > 
<center>
    @if ($labOrder->photo != null)
    <img class="w3-block w3-round" src="{{ url('/image/labOrder/') }}/{{ $labOrder->photo }}"  >
    @endif
</center>
<br>
<table class="w3-table" >
    <tr>
        <td>
            المريض / {{ ($labOrder->patient)? $labOrder->patient->name : '' }}
        </td>
        <td>
            شركة التامين / {{ ($labOrder->patient->insurance)? $labOrder->patient->insurance->name : '' }}
        </td>
    </tr>
    <tr>
        <td>
            معمل التحاليل / {{ ($labOrder->lab)? $labOrder->lab->name : '' }}
        </td>
        <td>
            موافقة شركة التامين / {!! $html !!}
        </td>
    </tr>
    <tr>
        <td> 
        </td>
        <td>
            كود شركة التامين / {{ $labOrder->insurance_code }}
        </td>
    </tr>
</table>
<table class="table table-bordered" >
    <tr>
        <td>الكود</td>
        <td>اسم التحليل</td>
    </tr>
    @foreach($labOrder->lab_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ $detail->analysis()->first()->name }}</td>
    </tr> 
    @endforeach
</table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <br>
                        <br>
                        <br>
 
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>


        </div>
    </body>

    <!-- js files -->
    {!! view("layout.js"); !!}

    <!-- datatable plugins -->
    {!! view("template.datatable-plugins"); !!}


    <!-- dashboard message file -->
    {!! view("template.msg") !!}
 

</html>
