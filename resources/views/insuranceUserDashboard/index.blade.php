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
$route = url("/insuranceuserdashboard/logout");
$name = $user->name;
$title = "الطلبات التى تحتاج الى موافقه";
@endphp

        @extends("layout.topbar", ["route" => $route, "name" => $name])

        @section("menus")
        <li class="dropdown user user-menu">
            <a href="#" onclick="openWindow('{{ url("/report/insurance/pharmacyorder") }}', 'تقرير طلبات الصيدليات')" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url('/') }}/image/pharmacy.png" class="user-image" alt="">
                <span class="hidden-xs">تقرير طلبات الصيدليات</span>
            </a> 
        </li> 
        <li class="dropdown user user-menu">
            <a href="#" onclick="openWindow('{{ url("/report/insurance/laborder") }}', 'تقرير طلبات معامل التحاليل')" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url('/') }}/image/lab.png" class="user-image" alt="">
                <span class="hidden-xs">تقرير طلبات معامل التحاليل</span>
            </a> 
        </li> 
        <li class="dropdown user user-menu">
            <a href="#" onclick="openWindow('{{ url("/report/insurance/radiologyorder") }}', 'تقرير طلبات معامل الاشعه')" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url('/') }}/image/radiology.png" class="user-image" alt="">
                <span class="hidden-xs">تقرير طلبات معامل الاشعه</span>
            </a> 
        </li>  
        <li class="dropdown user user-menu">
            <a href="#" onclick="openWindow('{{ url("/report/insurance/clinicorder") }}', 'تقرير حجزات العيادات')" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url('/') }}/image/hospital.png" class="user-image" alt="">
                <span class="hidden-xs">تقرير حجزات العيادات</span>
            </a> 
        </li> 
        @endsection

        
        <div class="w3-display-topleft" style="z-index: 9999999999"> 
            <div class="w3-white shadow w3-animate-zoom" style="width: 230px" >
                <center>
                    <img src="{{ url('/') }}/image/insurance/{{ $user->insurance->photo }}" class="w3-margin-right" height="50px"   > 
                    <span class="label label-primary w3-margin-right" >{{ $user->insurance->name }}</span>
                </center>
            </div>
        </div>
        
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
                            <table class="table" id="table" >
                                <thead>
                                    <tr>
                                        <th>الكود</th>
                                        <th> الصيدليه</th>
                                        <th>المريض</th>
                                        <th>موافقة شركة التامين</th>
                                        <th>كود التامين</th>
                                        <th>الصورة</th>
                                        <th>ملاحظات</th>
                                        <th>تاريخ الانشاء</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <br>
                        <br>
<div class="box shadow w3-round" style="border: 0px" >
    <div class="box-header"> 
        <span> طلبات معامل التحاليل</span>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive" id="tableContainer" >
        <table class="table" id="table2" >
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>معمل التحاليل</th>
                    <th>المريض</th>
                    <th>موافقة شركة التامين</th>
                    <th>كود التامين</th>
                    <th>الصورة</th>
                    <th>ملاحظات</th>
                    <th>تاريخ الانشاء</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<br> 
<div class="box shadow w3-round" style="border: 0px" >
    <div class="box-header"> 
        <span> طلبات معامل الاشعه</span>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive" id="tableContainer" >
        <table class="table" id="table3" >
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>معمل الاشعه</th>
                    <th>المريض</th>
                    <th>موافقة شركة التامين</th>
                    <th>كود التامين</th>
                    <th>الصورة</th>
                    <th>ملاحظات</th>
                    <th>تاريخ الانشاء</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<br>
<!-- send message modal -->
<div class="w3-modal notesModal" style="background-color: rgba(0,0,0,0.1)!important;z-index: 999999999999" >
    <div class="w3-modal-content shadow w3-round w3-padding w3-animate-top" >
        <label>هل تود اضافة ملاحظات على الطلب</label>
        <textarea class="form-control" id="notes" placeholder="قم بكتابة ملاحظات على الطلب" ></textarea>
        <br>
        <center>
            <button class="btn btn-primary" onclick="setRequest()" >
               ارسال <i class="fa fa-send" ></i>
            </button>
        </center>
    </div>
</div>

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

<script>
    function datatables() {
        $('#table').DataTable({
            "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('insuranceuserdashboardpharmacyData') }}",
                    "columns": [
                    {"data": "id"},
                    {"data": "pharmacy"},
                    {"data": "patient"},
                    {"data": "insurance_accept"},
                    {"data": "insurance_code"},
                    {"data": "image"},
                    {"data": "notes"},
                    {"data": "created_at"},
                    {"data": "action"}
                    ]
        });
        $('#table2').DataTable({
        "processing": true,
                "serverSide": true,
                "ajax": "{{ route('insuranceuserdashboardlabData') }}",
                "columns": [
                {"data": "id"},
                {"data": "lab"},
                {"data": "patient"},
                {"data": "insurance_accept"},
                {"data": "insurance_code"},
                {"data": "image"},
                {"data": "notes"},
                {"data": "created_at"},
                {"data": "action"}
                ]
        });
        $('#table3').DataTable({
        "processing": true,
                "serverSide": true,
                "ajax": "{{ route('insuranceuserdashboardradiologyData') }}",
                "columns": [
                {"data": "id"},
                {"data": "radiology"},
                {"data": "patient"},
                {"data": "insurance_accept"},
                {"data": "insurance_code"},
                {"data": "image"},
                {"data": "notes"},
                {"data": "created_at"},
                {"data": "action"}
                ]
        });
    }
          // radiology order ajax datatable 
    $(document).ready(function () {
        datatables();
    });
            var urlTemp = "";
            var buttonTemp = "";
            function acceptOrder(url, button) {
                urlTemp = url;
                buttonTemp = button;
                
                $(".notesModal").show(); 
            }
            
            function setRequest() { 
                button = buttonTemp;
                notes = $("#notes").val();
                $(".notesModal").hide(); 
                url = urlTemp + "?notes="+notes;
                
                $(button).html("<i class='fa fa-spin fa-spinner' ></i>");
                    $.get(url, function (data) {
                    if (data.status == 1) {
                    success(data.message, 
                    '{{ $user->insurance->name }}',
                    '{{ url('/') }}/image/insurance/{{ $user->insurance->photo }}');
                            // remove row
                            $(button).parent().parent().parent().remove();
                    } else {
                        error(data.message, 
                    '{{ $user->insurance->name }}',
                    '{{ url('/') }}/image/insurance/{{ $user->insurance->photo }}');
                        }
                    });
                playSound("send");
            }

    setNicescroll();
    
    // set listner to new orders after 5 seconds
//    setInterval(function(){
//        $("table tr th").click();
//    }, 15000);
</script>
    <script>
        setIcon();
        $(".frame").css("height", (window.innerHeight - 50) + "px");
        $(".frame").css("margin-top", "18px");

    </script>

</html>
