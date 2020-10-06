 
@extends("layout.reportTemplate")

@section("reportTitle")
model places <br>
report
@endsection

@section("reportContent")

<div class="" >
    <div id="map" class="w3-round" style="height: 500px" ></div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div class="w3-col l7 m7 s12" >
        <div class="w3-row" >
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box  w3-light-grey" href="clinic" >
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
                <a class="info-box  w3-light-grey" href="pharmacy" >
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
        </div>


        <div class="w3-row">
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box w3-light-grey" href="lab" >
                    <span class="info-box-icon bg-danger">
                        <i class="icon" name="lab.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lab</span>
                        <span class="info-box-number">{{ App\Lab::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>
            <div class=" w3-col l6 m6 s12  w3-padding" >
                <a class="info-box w3-light-grey" href="radiology" >
                    <span class="info-box-icon bg-fuchsia">
                        <i class="icon" name="radiology.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Radiology</span>
                        <span class="info-box-number">{{ App\Radiology::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>
        </div>

        <div class="w3-row">
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box  w3-light-grey" href="icu" >
                    <span class="info-box-icon bg-maroon">
                        <i class="icon" name="monitor.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">icu</span>
                        <span class="info-box-number">{{ App\Icu::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>
            <div class="w3-col l6 m6 s12 w3-padding" >
                <a class="info-box w3-light-grey" href="incubation" >
                    <span class="info-box-icon bg-navy">
                        <i class="icon" name="incubator.png" width="50px" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Incubation</span>
                        <span class="info-box-number">{{ App\Incubation::count() }}<small></small></span>
                    </div>
                    <!-- /.info-box-content -->
                </a>
            </div>

        </div>

    </div>
    
    <div class="w3-col l5 m5 s12" >
        <div id="piechart" class="w3-block" style="height: 400px" ></div>
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
                    @foreach($lnglats as $latlng)
                    [{{ $latlng["lat"] }}, {{ $latlng["lng"] }}, '{{ $latlng["name"] }}'],
                    @endforeach
            ]);
                    var options = {
                    mapType: 'terrain',
                            zoomLevel: 3,
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
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['model', 'number'],
          ['clinic',     {{ App\Clinic::count() }}],
          ['lab',      {{ App\Lab::count() }}],
          ['pharmacy',  {{ App\Pharmacy::count() }}],
          ['radiology', {{ App\Radiology::count() }}],
          ['incubation', {{ App\Incubation::count() }}],
          ['icu',    {{ App\Icu::count() }}]
        ]);

        var options = {
          title: 'models system numbers'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection