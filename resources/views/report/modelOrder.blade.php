
@extends("layout.reportTemplate")

@section("reportTitle")
model orders <br>
report
@endsection

@section("reportOptions")
<form action="" method="get" >
    <div class="w3-row box card" style="width: 70%;margin: auto;padding: 10px" >
        <div class="box-title" >
            select orders between two dates
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
            <a href="{{ url('/report/clinicorder') }}" class="waves-effect waves-light btn w3-red">select all</a>
        </div>
    </div>
</form>
<br>
<br>
@endsection

@section("reportContent")

<div class="" >
    <div id="chart_div" class="w3-round" style="height: 400px" ></div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div class="w3-col l3 m3 s12 w3-padding" >
        <a class="info-box  w3-light-grey" href="clinicorder" >
            <span class="info-box-icon bg-aqua">
                <i class="icon" name="clinicorder.png" width="50px" ></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">clinic orders</span>
                <span class="info-box-number">{{ App\ClinicOrder::count() }}<small></small></span>
            </div>
            <!-- /.info-box-content -->
        </a>
    </div>
    <div class="w3-col l3 m3 s12 w3-padding" >
        <a class="info-box  w3-light-grey" href="laborder" >
            <span class="info-box-icon bg-aqua">
                <i class="icon" name="laborder.png" width="50px" ></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">lab orders</span>
                <span class="info-box-number">{{ App\LabOrder::count() }}<small></small></span>
            </div>
            <!-- /.info-box-content -->
        </a>
    </div>
    <div class="w3-col l3 m3 s12 w3-padding" >
        <a class="info-box  w3-light-grey" href="radiologyorder" >
            <span class="info-box-icon bg-aqua">
                <i class="icon" name="radiologyorder.png" width="50px" ></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">radiology orders</span>
                <span class="info-box-number">{{ App\RadiologyOrder::count() }}<small></small></span>
            </div>
            <!-- /.info-box-content -->
        </a>
    </div>
    <div class="w3-col l3 m3 s12 w3-padding" >
        <a class="info-box  w3-light-grey" href="pharmacyorder" >
            <span class="info-box-icon bg-blue">
                <i class="icon" name="pharmacyorder.png" width="50px" ></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">pharmacy orders</span>
                <span class="info-box-number">{{ App\PharmacyOrder::count() }}<small></small></span>
            </div>
            <!-- /.info-box-content -->
        </a>
    </div>
</div>

<div>

    <table class="table table-bordered  "  id="table">
        <thead>
            <tr >
                <th>date</th>
                <th>number of orders</th>
                <th>orders type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clinicorders as $clinicorder)
            <tr>
                <td>{{ $clinicorder->d }}</td>
                <td>{{ $clinicorder->number }}</td>
                <td>
                    <span class="label label-primary" >clinic</span>
                </td>
            </tr>
            @endforeach
            @foreach($laborders as $laborder)
            <tr>
                <td>{{ $laborder->d }}</td>
                <td>{{ $laborder->number }}</td>
                <td>
                    <span class="label label-success" >lab</span>
                </td>
            </tr>
            @endforeach
            @foreach($radiologyorders as $radiologyorder)
            <tr>
                <td>{{ $radiologyorder->d }}</td>
                <td>{{ $radiologyorder->number }}</td>
                <td>
                    <span class="label label-info" >radiology</span>
                </td>
            </tr>
            @endforeach
            @foreach($pharmacyorders as $pharmacyorder)
            <tr>
                <td>{{ $pharmacyorder->d }}</td>
                <td>{{ $pharmacyorder->number }}</td>
                <td>
                    <span class="label label-danger" >pharmacy</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
            setIcon();
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                    ['date', 'clinic orders', 'lab orders', 'radiology orders', 'pharmacy orders'],
                    @foreach($clinicorders as $clinicorder)
                    ['{{ $clinicorder->d }}', {{ $clinicorder->number }}, 0, 0, 0],
                    @endforeach

                    @foreach($laborders as $laborder)
                    ['{{ $laborder->d }}', 0, {{ $laborder->number }}, 0, 0],
                    @endforeach

                    @foreach($radiologyorders as $radiologyorder)
                    ['{{ $radiologyorder->d }}', 0, 0, {{ $radiologyorder->number }}, 0],
                    @endforeach

                    @foreach($pharmacyorders as $pharmacyorder)
                    ['{{ $pharmacyorder->d }}', 0, 0, 0, {{ $pharmacyorder->number }}],
                    @endforeach
            ]);
                    var options = {
                    title: 'orderes number per day',
                            hAxis: {title: 'date', titleTextStyle: {color: '#333'}},
                            vAxis: {minValue: 0}
                    };
                    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
            }
            $('.datepicker').datepicker();
            var instance = M.Datepicker.getInstance(document.getElementById("datefrom"));
</script>
</script>
@endsection
