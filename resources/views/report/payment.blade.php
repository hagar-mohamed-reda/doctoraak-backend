
@extends("layout.reportTemplate")

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
            <a href="{{ url('/report/payment') }}" class="waves-effect waves-light btn w3-red">select all</a>
        </div>
    </div>
</form>
<br>
<br>
@endsection
@section("reportTitle")
payment report
@endsection

@section("reportContent")

<div class="" >
    <div id="paymentValues" style="width: 100%; height: 500px"></div>
</div>
<br>
<br>
<div class="w3-row font" >
    <div class="w3-col l7 m7 s12" >
        <table class="table table-bordered text-right table-right" id="table" >
            <thead>
                <tr>
                    <td>id</td>
                    <td>type</td>
                    <td>name</td>
                    <td>value</td>
                    <td>date</td>
                </tr>
            </thead>
            <tbody>
                @foreach($pays as $pay)
                <tr>
                    <td>{{ $pay->id }}</td>
                    <td><span class="label {{ App\helper\Helper::randColor() }} shadow" >{{ $pay->getModelName() }}</span></td>
                    <td>{{ $pay->getName() }}</td>
                    <td>{{ $pay->value }}</td>
                    <td>{{ date("Y-m-d", strtotime($pay->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="w3-col l5 m5 s12" >
        <div id="piechart" class="w3-block" style="height: 400px" ></div>
    </div>
</div>
@endsection

@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['date', 'value'],
      @foreach($pays as $pay)
      ['{{ date("Y-m-d", strtotime($pay->created_at)) }}',  {{ $pay->value }}],
      @endforeach
    ]);

    var options = {
      title: 'payment per day',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('paymentValues'));

    chart.draw(data, options);
  }
</script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['model', 'number'],
          @foreach($paymentModels as $paymentModel)
          ['{{ $paymentModel["name"] }}',     {{ $paymentModel["value"] }}],
          @endforeach
        ]);

        var options = {
          title: 'payment models system value'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection
