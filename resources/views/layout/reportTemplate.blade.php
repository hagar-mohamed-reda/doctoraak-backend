<!doctype html>
<html lang="">
    <head>

        <!-- loader files -->

        <!-- css files -->
        {!! view("layout.css"); !!}

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, category-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/select2/dist/css/select2.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('/') }}/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{ url('/') }}/dist/css/skins/skin-blue.min.css">
        <link rel="stylesheet" href="{{ url('/') }}/dist/css/skins/skin-blue.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.material.min.css">


        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="{{ url('/') }}/plugins/timepicker/bootstrap-timepicker.min.css">

        <!-- my style -->
        <link rel="stylesheet" href="{{ url('/') }}/css/w3.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/iziToast.min.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/app.css">

        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        @yield("styles")

        <!-- Google Font-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        {!! view("template.commen") !!}

        <script>
            var public_path = '{{ url("/") }}';
        </script>

        <style>
            .report-content {
                padding: 20px;
                margin: auto;
                width: 70%;
            }

            .report-margin, .line {
                margin-left: 2%;
            }

            .report-title {
                color: #367fa9;
                font-family: consolas;
            }

            .consolas {
                font-family: consolas;
            }

            .line {
                border: 1px solid #367fa9;
                width: 90%;
                border-radius: 16px;
            }

            .report-title {
                letter-spacing: 3px;
                margin-bottom: -10px;
                margin-left: 0;
            }
            
            .info-box {
                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                border-radius: .25rem;
                padding: .5rem;
                min-height: 80px;
                background: #fafafa!important;
            }
            
            .info-box .info-box-icon {
                border-radius: .25rem;
                display: block;
                width: 67px!important;
                height: 67px!important;
                text-align: center;
                line-height: 60px!important;
                font-size: 30px;
            }
            
            .info-box .info-box-text { 
                font-size: 11px!important;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper margin-0"   >

            <!-- Content Header (Page header) -->
            <section class="w3-padding content-header font">
                <h2 class="font" >
                    @yield("title")
                </h2>
            </section>

            <section class="content w3-row">
                <div>
                    @yield("reportOptions")
                </div>

                <div class="card w3-white report-content" style="margin: auto;">
                    <div class="card-content report-margin w3-border-0 w3-display-container">
                        <br>
                        <div class="report-title w3-xxlarge text-uppercase" >
                            @yield("reportTitle")
                        </div>
                        <div class="w3-display-topright w3-padding" >
                            <img src="{{ url('image/reportLogo.png') }}" width="60px" >
                            <br>
                            <div class="consolas" >Doctoraak</div>

                        </div>
                    </div>
                    <div class="line" ></div>

                    <div class="card-action report-margin  w3-border-0">
                        @yield("reportContent")
                    </div>
                </div>
            </section>


        </div>
    </body>

    <!-- jQuery 3 -->
    <script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ url('/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="{{ url('/') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- DataTables -->
    <script src="{{ url('/') }}/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{{ url('/') }}/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="{{ url('/') }}/bower_components/select2/dist/js/select2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('/') }}/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ url('/') }}/dist/js/demo.js"></script>
    <!-- nicescroll -->
    <script src="{{ url('/') }}/js/jquery.nicescroll.min.js"></script>

    <!-- time picker -->
    <script src="{{ url('/') }}/plugins/timepicker/bootstrap-timepicker.min.js"></script>

    <!-- datatable plugins-->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.material.min.js"></script>

    <!-- my scripts -->
    <script src="{{ url('/') }}/js/iziToast.min.js"></script>
    <script src="{{ url('/') }}/js/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/js/app.js"></script>
    <script src="{{ url('/') }}/js/category.js"></script>

    @yield("scripts")

    <script>

        dataTable();
 

    </script>
</html>
