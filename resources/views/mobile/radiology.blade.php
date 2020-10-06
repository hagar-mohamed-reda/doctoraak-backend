<?php
        if (!isset($_GET["radiology"]))
        exit();

    $lang = "en";

    if (isset($_GET["lang"]))
        $lang = $_GET["lang"];

    $radiology = App\Radiology::find($_GET["radiology"]);


    if (!$radiology)
        exit();
?>

<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">



<!-- Font Awesome -->
<link rel="stylesheet" href="{{ url('/') }}/bower_components/font-awesome/css/font-awesome.min.css">

<!-- jQuery 3 -->
<script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
@if ($lang == "en")
<link href="https://fonts.googleapis.com/css?family=Text+Me+One&display=swap" rel="stylesheet">
@else
<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
@endif


<style>

    * {
        @if ($lang == "en")
            font-family: 'Text Me One', sans-serif;
        @else
            font-family: 'Cairo', sans-serif;
        @endif
    }

    .info-box {
        background-image: url('{{ url('/') }}/image/header.jpg');
        background-size: cover;
        position: relative;
        min-height: 150px;
        width: 300px;
        float: left;
        margin: 10px;
    }

    .info-box-container {
        height: 120px;
        width: {{ 4 * 350 }}px;
    }

    .info-box-row {
        overflow: auto;
    }

    .hover {
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        position: absolute;
        opacity: 0.4;
        z-index: 1;
    }

    .hover-info {
        opacity: 1;
        z-index: 2;
    }

    .transition {
        transition: all 1.5s ease-in-out;
    }
</style>
<body>

    <div class="w3-row info-box-row" >
        <div class="w3-padding info-box-container" style="height: 120px" >

            <div class="z-depth-1 w3-round w3-white w3-display-container card info-box w3-animate-left transition" >
                <div class="hover {{ App\helper\Helper::randColor() }}" >

                </div>
                <div class="hover-info hover w3-padding" >
                    <div class="w3-display-topright w3-padding" >
                            <i class="fa fa-user-md w3-xxlarge w3-text-white" ></i>
                    </div>
                    <b class="w3-xlarge w3-text-white" >{{ ($lang=="en")? "radiology reservation" : 'حجزات المعمل'  }}</b>
                    <br>
                    <span class="w3-xxxlarge w3-text-white" >{{ $radiology->orders()->count() }}</span>

                </div>
            </div>
            <div class="z-depth-1 w3-round w3-white w3-display-container card info-box w3-animate-left transition" >
                <div class="hover {{ App\helper\Helper::randColor() }}" >

                </div>
                <div class="hover-info hover w3-padding" >
                    <div class="w3-display-topright w3-padding" >
                            <i class="fa fa-suitcase w3-xxlarge w3-text-white" ></i>
                    </div>
                    <b class="w3-large w3-text-white" >{{ ($lang=="en")? "number of working days" : "عدد ايام العمل" }}</b>
                    <br>
                    <span class="w3-xxxlarge w3-text-white" >{{ $radiology->working_hours()->where('active', '1')->count() }}</span>

                </div>
            </div>
            <div class="z-depth-1 w3-round w3-white w3-display-container card info-box w3-animate-left transition" >
                <div class="hover {{ App\helper\Helper::randColor() }}" >

                </div>
                <div class="hover-info hover w3-padding" >
                    <div class="w3-display-topright w3-padding" >
                            <i class="fa fa-medkit w3-xxlarge w3-text-white" ></i>
                    </div>
                    <b class="w3-large w3-text-white" >{{ ($lang=="en")? "rays number" : "عدد الاشعه المتاحه " }}</b>
                    <br>
                    <span class="w3-xxxlarge w3-text-white" >{{ App\Ray::count() }}</span>

                </div>
            </div>
            <div class="z-depth-1 w3-round w3-white w3-display-container card info-box w3-animate-left transition" >
                <div class="hover {{ App\helper\Helper::randColor() }}" >

                </div>
                <div class="hover-info hover w3-padding" >
                    <div class="w3-display-topright w3-padding" >
                            <i class="fa fa-ambulance w3-xxlarge w3-text-white" ></i>
                    </div>
                    <b class="w3-large w3-text-white" >{{ ($lang=="en")? "insurance number" : "شركات التامين" }}</b>
                    <br>
                    <span class="w3-xxxlarge w3-text-white" >{{ $radiology->radiology_insurances()->count() }}</span>

                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col s12">

            <br>
            <div class="w3-padding" >
                <canvas id="myChart" height="200"></canvas>
            </div>

            <br>

            <div class="w3-ul" >
                <?php
                    $flag = 1;
                ?>
                @foreach($radiology->getChartData() as $key => $value)

                <li class="transition wow {{ $flag == 1? 'w3-animate-left' : 'w3-animate-right' }}" >
                    <div class="w3-white w3-padding z-depth-1 w3-display-container" >
                        <i class="fa fa-calendar w3-large w3-margin-right w3-text-indigo" ></i>
                        {{ $key }}
                        <div class="w3-display-topright w3-padding" >
                            <span class="badge w3-indigo w3-round transition w3-animate-zoom" >
                                <i class="fa fa-user-md" ></i>
                                {{ $value }}
                            </span>
                        </div>
                    </div>
                </li>
                <?php $flag *= -1; ?>
                @endforeach
            </div>
        </div>

    </div>





    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script>
        $('.tabs').tabs();
        new WOW().init();
    </script>
    <?php
    $counter = 1;
    ?>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($radiology->getChartData() as $key => $value)
                    '{{ $key }}',
                    @endforeach
                ],
                datasets: [{
                    label: '{{ $lang == "en"? "reservation of radiology per day" : "حجزات المعمل فى اليوم"  }}',
                    data: [
                    @foreach($radiology->getChartData() as $key => $value)
                    {{ $value }},
                    @endforeach
                    ],
                    backgroundColor: [
                        'rgba(115, 54, 243, 0.2)',
                    ],
                    borderColor: [
                        'rgba(115, 54, 243, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
</script>
</body>
</html>
