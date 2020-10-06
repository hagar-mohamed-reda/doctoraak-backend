@php


$lang = "en";
@endphp

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
            /*font-family: 'Text Me One', sans-serif; */
        @else
            font-family: 'Cairo', sans-serif;
        @endif
    }
    
    body, html {
        background-color: #FAFAFA;
        transition: all 1.5s ease-in-out;
        overflow: hidden; 
    }
    
    .info-box {
        background-image: url('http://doctoraak.sphinxat.us/public/image/header.jpg');
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
    
    .transition, body {
        transition: all .5s ease;
    }
    
    .header {
        background-image: url('{{ url("/mobile/ic_toolbar_full.png") }}');
        background-size: 100% 100%;
        background-position: center top;
        background-repeat: no-repeat;
        width: 100%;
        height: 300px;
    }
    
    .item {
        border-radius: 7px;
        background-color: white;
        overflow: hidden;
    }
    
    .shadow {
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2)!important;
    }
    
    .image-container {
        padding: 20px;
        /*
        background-image: linear-gradient(#AC7FFA 10%, #707070 90%);*/
        background-color: #AC7FFA;
    }
    
    .ul-item {
        padding: 5px!important;
    }
    
    .title {
        padding: 40px;
    }
</style>
<body>
    
    <div class="application" style="overflow: hidden" >
        
        <div class="header" > 
            <div class="title w3-text-white w3-jumbo transition" >
                Doctor
            </div> 
        </div>  
        <div class="w3-ul w3-padding content-data" >
            @foreach(App\Specialization::all() as $item)
            <li class="ul-item w3-col l4 m4 s4" >
                <div class="item shadow" >
                    <center class="image-container" >
                        <img src="{{ $item->url }}" width="60%" />
                    </center> 
                    <center class="w3-padding" >
                        {{ substr($item->name, 0, 6) }}..
                    </center>
                </div>
            </li>
            @endforeach
            
            
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
        
        
        var position = $(window).scrollTop(); 
        /*$(window).scroll(function() {
            var scroll = $(window).scrollTop();    
             

            if(scroll > position) {          // scroll top
                $(document).css("overflow", "hidden");
                $(".header").css("background-image", "url('{{ url("/mobile/ic_toolbar_half.png") }}')");
                $(".header").css("height", "150px");
            } else {                         // scroll bottom
                //$(document).css("overflow", "hidden");
                //$(".header").css("background-image", "url('{{ url("/mobile/ic_toolbar_full.png") }}')");
                //$(".header").css("height", "250px");
                
                console.log(scroll);
            }
 
            position = scroll;  
        });*/ 
        var lastY;
        var full = true;
        var mini = false;
        $(document).bind('touchmove', function (e){
            var scroll = $(".content-data").scrollTop();
             var currentY = e.originalEvent.touches[0].clientY;
             if(currentY > lastY){
                 // moved down
                if (scroll == 0 && !full) {
                    $(".header").css("background-image", "url('{{ url("/mobile/ic_toolbar_full.png") }}')"); 
                    $(".header").animate({
                        height: 300
                    }, 500);
                    $(".content-data").css("height", (window.innerHeight - 150) + "px");
                    $(".content-data").css("overflow", "hidden");
                    //
                    full = true;
                    mini = false;
                    $(".title").removeClass("w3-xxxlarge");
                    $(".title").addClass("w3-jumbo");
                }
             }else if(currentY < lastY){
                 if (full && !mini) {
                    // moved up 
                    $(".header").css("background-image", "url('{{ url("/mobile/ic_toolbar_half.png") }}')"); 
                    $(".header").animate({
                        height: 150
                    }, 500);
                    $(".content-data").css("height", (window.innerHeight - 150) + "px");
                    $(".content-data").css("overflow", "auto");
                    full = false;
                    mini = true;
                    $(".title").removeClass("w3-jumbo");
                    $(".title").addClass("w3-xxxlarge");
                 }
                
                //alert();
             }
             lastY = currentY;
        }); 
        
    </script>
    <?php 
    $counter = 1;
    ?>  
</body>
</html> 
