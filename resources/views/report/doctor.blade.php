
@extends("layout.reportTemplate")

@section("reportTitle")
doctors report
@endsection


@section("reportContent")
<div class="w3-border-light-blue w3-round" style="border: 1px solid;" >
    <div class="w3-large w3-padding" >
        doctor rates
    </div>
    <div class="nicescroll" style="width: 100%;height: 570px" >
        <div id="columnchart_values" style="height: 400px" ></div>
    </div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div>
        <center>
        <table class="table table-bordered text-center" id='table' >
            <thead>
                <tr class="w3-light-grey" >
                    <th>id</th>
                    <th>doctor</th>
                    <th>rate</th>
                </tr>
            </thead>
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->id }}</td>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->getRate() }}</td>
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

    var pixel = {{ count($doctors) }};
    var currentPixel = 10;

    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['doctor', 'rate', { role: "style" }],
        <?php $counter = 1; ?>
        @foreach($doctors as $doctor)
        ['{{ $doctor->name }}', {{ $doctor->getRate() }}, ""],
        @endforeach
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "doctor rates",
        width: {{ count($doctors) }},
        height: 500,
        bar: {width: 2, groupWidth: "100%"},
        legend: { position: "none" },
        animation:{
          duration: 1000,
          easing: 'out',
        },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }


    </script>
<script type="text/javascript">
        setIcon();
    var speed = 100;

    setInterval(function(){
        $("#columnchart_values").parent().scrollLeft(currentPixel);

        if (currentPixel > 3000)
            currentPixel = 10;

        currentPixel += 10;
    }, speed);
</script>
@endsection
