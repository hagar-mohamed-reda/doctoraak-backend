  
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="font w3-xxlarge" >
            لوحة التحكم
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">لوحة التحكم</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            
            
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>{{ App\Doctor::count() }}</h3>

                    <p>{{ __('doctors') }}</p>
                  </div>
                  <div class="icon">
                      <i class="fa fa-users" ></i>
                  </div>
                    <a href="#" class="small-box-footer" onclick="showPage('doctor')" >More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                 
                <!-- /.info-box -->
            </div>
            
            
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12"> 
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3>{{ App\Pharmacy::count() }}</h3>

                    <p>{{ __('pharmacies') }}</p>
                  </div>
                  <div class="icon">
                      <i class="fa fa-medkit" ></i>
                  </div>
                    <a href="#" class="small-box-footer" onclick="showPage('pharmacy')" >More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12"> 
                <div class="small-box bg-red">
                  <div class="inner">
                    <h3>{{ App\Lab::count() }}</h3>

                    <p>{{ __('labs') }}</p>
                  </div>
                  <div class="icon">
                      <i class="fa fa-thermometer" ></i>
                  </div>
                    <a href="#" class="small-box-footer" onclick="showPage('lab')" >More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <!-- /.info-box -->
            </div>

            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12"> 
                <div class="small-box bg-blue">
                  <div class="inner">
                    <h3>{{ App\Radiology::count() }}</h3>

                    <p>{{ __('radiologies') }}</p>
                  </div>
                  <div class="icon">
                      <i class="fa fa-thermometer" ></i>
                  </div>
                    <a href="#" class="small-box-footer" onclick="showPage('radiology')" >More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <!-- /.info-box -->
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">احصائيات شهريه</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body row">

                        <!--Div that will hold the pie chart-->
                        <div id="chart_div" class="col-lg-6 col-md-6 col-sm-6" ></div>

                        <div class="col-lg-6 col-md-6 col-sm-6 w3-padding" >
                            <div id="chart_div2" class="shadow w3-round" style="height: 300px" ></div>
                        </div>

                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div> 
 
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages': ['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);
            // Callback that creates and populates a data table,
                    // instantiates the pie chart, passes in the data and
                            // draws it.
                                    function drawChart() {

                                    // Create the data table.
                                    var data = new google.visualization.DataTable();
                                            data.addColumn('string', 'العنوان');
                                            data.addColumn('number', 'الزيارة');
                                            data.addRows([
                                                    @foreach($views as $view)
                                                    ['{{ $view->ip }}', {{ $view->view }}],
                                                    @endforeach
                                            ]);
                                            // Set chart options
                                            var options = {'title': 'معدل الزياره خلال الشهر',
                                                    'height': 300};
                                            // Instantiate and draw our chart, passing in some options.
                                            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                                            chart.draw(data, options);
                                    }
</script>

<script type="text/javascript">
//      google.charts.load('current', {
//        'packages':['map'],
//        // Note: you will need to get a mapsApiKey for your project.
//        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
//        'mapsApiKey': 'AIzaSyDzl7l4tzN1dWBVu3dL_62EkHteripsaqc'
//      });
//      google.charts.setOnLoadCallback(drawChart);
//      function drawChart() {
//        var data = google.visualization.arrayToDataTable([
//          ['Lat', 'Long', 'Name'],
//            @foreach($views as $view)
//                [{{ $view->lat }},{{ $view->lng }}, '{{ $view->ip}}'],
//            @endforeach
//        ]);
//
//        var options = {
//        mapType: 'terrain',
//        zoomLevel: 6,
//        showTooltip: true,
//        showInfoWindow: true,
//        useMapTypeControl: true,
//          icons: {
//            default: {
//              normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Azure-icon.png',
//              selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Right-Azure-icon.png'
//            }
//          }
//        };
//
//        var map = new google.visualization.Map(document.getElementById('chart_div2'));
//        map.draw(data, options);
//      }

</script>
<script>
                           // setIcon();
</script> 
