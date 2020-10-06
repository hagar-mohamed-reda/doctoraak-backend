
@extends("layout.reportTemplate")

@section("reportTitle")
Insurance report
@endsection


@section("reportContent")

<div class="" >
    <div id="series_chart_div" class="w3-block" style="height: 400px" ></div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div>
        <center>
        <table class="table table-bordered data-table text-center" id='table' >
            <thead>
                <tr  >
                    <th>id</th>
                    <th>insurance <i class="icon" name="insurance.png" width="20px" ></i></th>
                    <th>patient <i class="icon" name="patient.png" width="20px" ></i></th>
                    <th>doctor <i class="icon" name="doctor.png" width="20px" ></i></th>
                    <th>lab <i class="icon" name="lab.png" width="20px" ></i></th>
                    <th>radiology <i class="icon" name="radiology.png" width="20px" ></i></th>
                    <th>pharmacy <i class="icon" name="pharmacy.png" width="20px" ></i></th>
                </tr>
            </thead>
            @foreach($insurances as $insurance)
                <tr>
                    <td>{{ $insurance->id }}</td>
                    <td>{{ $insurance->name }} </td>
                    <td>{{ $insurance->patients->count() }} </td>
                    <td>{{ $insurance->doctor_insurances->count() }}</td>
                    <td>{{ $insurance->lab_insurances->count() }}</td>
                    <td>{{ $insurance->radiology_insurances->count() }}</td>
                    <td>{{ $insurance->pharmacy_insurances->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
            </center>
    </div>
</div>
@endsection

@php
$x = 1;
@endphp

@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['insurance', '', '', 'color',     'user numbers'],
        @foreach($insurances as $insurance)
            @php
                $userNumber = $insurance->patients->count() +
                              $insurance->doctor_insurances->count() +
                              $insurance->pharmacy_insurances->count() +
                              $insurance->lab_insurances->count() +
                              $insurance->radiology_insurances->count();
            @endphp
            ['{{ $insurance->name }}',    {{ $x }},  {{ $x ++ }},  '{{ $insurance->name }}',  {{ $userNumber }}],
        @endforeach
            ['',    {{ $x + 5 }},  {{ $x + 5 }},  '{{ $x + 5 }}',  0],
      ]);

      var options = {
        title: 'insurance company and there patients',
        hAxis: {title: 'Life Expectancy'},
        vAxis: {title: 'Fertility Rate'},
        bubble: {textStyle: {fontSize: 11}}      };

      var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      chart.draw(data, options);
    }
    </script>
<script type="text/javascript">
        setIcon();
</script>
@endsection
