@extends("layout.reportTemplate")

@php
    $resault = [];
@endphp

@section("reportTitle")
تقرير طلبات الصيدليات
@endsection

@section("reportOptions")
<form action="" method="get" >
    <div class="w3-row box card" style="width: 70%;margin: auto;padding: 10px" >
        <div class="box-title" >
            حدد تاريخ للبحث فى الطلبات
        </div>
        <div class="box-body" style="direction: ltr!important" >
            <div class="input-field w3-col l6 m6 s12">
                <i class="material-icons prefix fa fa-calendar"></i>
                <input id="datefrom" type="date" class="vali" name="datefrom" >
                <label for="datefrom">من تاريخ</label>
              </div>
              <div class="input-field w3-col l6 m6 s12">
                <i class="material-icons prefix fa fa-calendar"></i>
                <input id="dateto" type="date" class="vali" name="dateto" >
                <label for="dateto">الى تاريخ</label>
              </div>
        </div>
        <div class="box-footer" >
            <button class="waves-effect waves-light btn w3-blue">بحث</button> 
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

<div>

    <table class="table table-bordered " id="table-no-paging" >
        <thead>
            <tr>
                <th>الكود</th>
                <th>اسم الصيدليه</th>
                <th>اسم المريض</th>
                <th>موافقة شركة التامين</th>
                <th>كود شركة التامين</th> 
                <th>ملاحظات</th>
                <th>تاريخ الانشاء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ isset($item->pharmacy->name)? $item->pharmacy->name : '' }}</td>
                <td>{{ $item->patient->name }}</td>
                <td>{{ $item->insurance_accept }}</td>
                <td>{{ $item->insurance_code }}</td> 
                <td>{{ $item->notes }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
            
            @php 
                if (isset($resault[date("Y-m-d", strtotime($item->created_at))] ))
                    $resault[date("Y-m-d", strtotime($item->created_at))] += 1;
                else
                    $resault[date("Y-m-d", strtotime($item->created_at))] = 1;  
            @endphp
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
                    ['date', 'orders number'],
                    @foreach($resault as $key => $r)
                    ['{{ $key }}', {{ $r }}],
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
            
            dataTableWithoutPaging();
</script>
</script>
@endsection
