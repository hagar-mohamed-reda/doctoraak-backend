
@extends("layout.reportTemplate")

@section("reportTitle")
user view<br>
report
@endsection

@section("reportOptions")
<form action="" method="get" >
    <div class="w3-row box card" style="width: 70%;margin: auto;padding: 10px" >
        <div class="box-title" >
            select views between two dates
        </div>
        <div class="box-body" >
            <div class="input-field w3-col l6 m6 s12">
                <i class="material-icons prefix fa fa-calendar"></i>
                <input id="datefrom" type="date" class="vali" name="datefrom" >
                <label for="datefrom">date from</label>
              </div>
              <div class="input-field w3-col l6 m6 s12">
                <i class="material-icons prefix fa fa-calendar"></i>
                <input id="dateto" type="date" class="vali" name="dateto" >
                <label for="dateto">date to</label>
              </div>
        </div>
        <div class="box-footer" >
            <button class="waves-effect waves-light btn w3-blue">search</button>
            <a href="{{ url('/report/userview') }}" class="waves-effect waves-light btn w3-red">select all</a>
        </div>
    </div>
</form>
<br>
<br>
@endsection

@section("reportContent")

<div class="" >
    <div id="map" class="w3-round" style="height: 400px" ></div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div class="w3-col l7 m7 s12" >
        <div class=" w3-row" >
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box  w3-light-grey w3-round" href="clinic" >
                    <span class="info-box-icon bg-aqua">
                        <i class="icon" name="hospital.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">clinics</span>
                        <span class="info-box-number">{{ App\Clinic::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box  w3-light-grey w3-round" href="pharmacy" >
                    <span class="info-box-icon bg-blue">
                        <i class="icon" name="pharmacy.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">pharmacy</span>
                        <span class="info-box-number">{{ App\Pharmacy::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>
        </div >
        <div>
            <table class="table table-bordered e" id='table' >
                <thead>
                    <tr >
                        <th>ip</th>
                        <th>date</th>
                        <th>view</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($views as $view)
                    <tr>
                        <td>{{ $view->ip }}</td>
                        <td>{{ $view->date }}</td>
                        <td>{{ $view->view }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="w3-col l5 m5 s12" >
            <div id="chart_div" class="w3-block" style="height: 400px" ></div>
    </div>

    </div>

</div>
@endsection

@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    setIcon();
      google.charts.load('current', {
        'packages':['map'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': 'AIzaSyDzl7l4tzN1dWBVu3dL_62EkHteripsaqc'
      });
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Lat', 'Long', 'Name'],
            @foreach($views as $view)
                [{{ $view->lat }},{{ $view->lng }}, '{{ $view->ip}}'],
            @endforeach
        ]);

        var options = {
        mapType: 'terrain',
        zoomLevel: 6,
        showTooltip: true,
        showInfoWindow: true,
        useMapTypeControl: true,
          icons: {
            default: {
              normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Azure-icon.png',
              selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Right-Azure-icon.png'
            }
          }
        };

        var map = new google.visualization.Map(document.getElementById('map'));
        map.draw(data, options);
      }

</script>
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
@endsection
