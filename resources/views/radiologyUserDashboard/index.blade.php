 

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
        $route = url("/radiologydoctordashboard/logout");
        $name = $user->name;
        $title = " حجزات معامل الاشعه";
        @endphp  

        {!! view("layout.topbar", compact("route", "name")) !!}
        <div class="content-wrapper" style="margin: 0px"  >  
            @include("layout.broadcamb", compact("title"))

            <!-- Main content -->
            <section class="content  frame">


                <div class="row">
                    <div class="col-xs-12">
                        <!-- /.box -->

                        <div class="box shadow w3-round nicescroll" style="border: 0px" >
                            <div class="box-header">
                                حجزات معامل الاشعه
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive" id="tableContainer" > 
                                <table class="table" id="table" >
                                    <thead>
                                        <tr> 
                                            <th>معمل الاشعه</th>
                                            <th>المريض</th>
                                            <th>موافقة شركة التامين</th>
                                            <th>كود التامين</th>
                                            <th>الصورة</th>
                                            <th>ملاحظات</th> 
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
        $(document).ready(function () {
            $('#table').DataTable({
                "processing": true,
                "serverSide": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "order": [[0, "desc"]],
                "ajax": "{{ route('radiologydoctordashboardorderData') }}",
                "columns": [
                    {"data": "radiology"},
                    {"data": "patient"},
                    {"data": "insurance_accept"},
                    {"data": "insurance_code"},
                    {"data": "image"},
                    {"data": "notes"}, 
                    {"data": "action"}
                ]
            });
        });
    </script>
    <script>
        setIcon();
        $(".frame").css("height", (window.innerHeight - 50) + "px");
        $(".frame").css("margin-top", "18px");

    </script>

</html>
