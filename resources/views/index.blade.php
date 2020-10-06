<!doctype html>
<html lang="">
    <head>
        
         
        
        {!! view("layout.css") !!}
        
        {!! view("template.commen") !!}


        <script> 
            var public_path = '{{ url("/") }}';
        </script>
        <style> 
            .content-wrapper {
                overflow: hidden;
            } 
        </style>
 
        <style>
            table {
                direction: rtl;
            }

            .content-wrapper {
                direction: rtl;
                overflow: auto!important;
            }

            .breadcrumb {
                left: 10px!important;
                right: auto!important;
            }

            .content-wrapper {
                max-height: 664px!important;
            }
            .fixed .content-wrapper, .fixed .right-side {
                padding-top: 35px!important;
            }
        </style>
        {!! view("template.commen") !!}
    </head>
    
    <body class="hold-transition skin-blue-light fixed sidebar-mini"  >

        {!! view("template.navbar") !!}
        
        {!! view("template.topbar", compact("user")) !!} 
        <div class="content-wrapper"  >  
            <div class="frame" ></div>
    

        </div>
    </body>
    
    <!-- js files -->
    {!! view("layout.js"); !!}

    <!-- datatable plugins -->
    {!! view("template.datatable-plugins"); !!}


    <!-- dashboard message file -->
    {!! view("template.msg") !!}

    <script>
            setIcon(); 
            $(".frame").css("height", (window.innerHeight - 50) + "px");
            $(".frame").css("margin-top", "18px");
            
            showPage('dashboard');
    </script>
 
</html>
