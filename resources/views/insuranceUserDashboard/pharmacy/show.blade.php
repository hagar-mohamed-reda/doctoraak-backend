<?php
if ($pharmacyOrder->insurance_accept == 'accepted') {
    $pharmacyel = "success";
    $text = "موافقه";
} else if ($pharmacyOrder->insurance_accept == 'refused') {
    $pharmacyel = "danger";
    $text = "مرفوض"; 
} else if ($pharmacyOrder->insurance_accept == 'required') {
    $pharmacyel = "warning";
    $text = "مطلوب";
} else {
    $pharmacyel = "default";
    $text = "غير متاحه";
}

$html = "<span class='pharmacyel pharmacyel-$pharmacyel' >$text</span>";

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
        $title = "طلب الصيدليه رقم " . $pharmacyOrder->id;
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
    @if ($pharmacyOrder->photo != null)
    <img class="w3-block w3-round" src="{{ url('/image/pharmacyOrder/') }}/{{ $pharmacyOrder->photo }}"  >
    @endif
</center>
<br>
<table class="w3-table" >
    <tr>
        <td>
            المريض / 
            {{ ($pharmacyOrder->patient)? $pharmacyOrder->patient->name : '' }}
        </td>
        <td>
            شركة التامين 
            / {{ ($pharmacyOrder->patient->insurance)? $pharmacyOrder->patient->insurance->name : '' }}
        </td>
    </tr>
    <tr>
        <td>
            الصيدليه  /
            {{ ($pharmacyOrder->pharmacy)? $pharmacyOrder->pharmacy->name : '' }}
        </td>
        <td>
            موافقة شركة التامين / {!! $html !!}
        </td>
    </tr>
    <tr>
        <td> 
        </td>
        <td>
            كود شركة التامين / {{ $pharmacyOrder->insurance_code }}
        </td>
    </tr>
</table>
<table class="table table-bordered" >
    <tr>
        <td>الكود</td>
        <td>اسم الدواء</td>
        <td>نوع الدواء</td>
    </tr>
    @foreach($pharmacyOrder->pharmacy_order_details()->get() as $detail)
    <tr>
        <td>{{ $counter ++ }}</td>
        <td>{{ $detail->medicine()->first()->name }}</td>
        <td>{{ $detail->medicine_type()->first()->name }}</td>
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
