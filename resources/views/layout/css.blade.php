

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title></title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ url('/') }}/bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ url('/') }}/bower_components/Ionicons/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('/') }}/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{ url('/') }}/dist/css/AdminLTE_ar.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. --> 
<link rel="stylesheet" href="{{ url('/') }}/dist/css/skins/_all-skins.min.css"> 

<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{ url('/') }}/plugins/timepicker/bootstrap-timepicker.min.css">

<link rel="stylesheet" href="{{ url('/') }}/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{ url('/') }}/Bootstrap-RTL-3.3.7.3/3.3.7/bootstrap.rtl.full.min.css">

<!-- my style -->
<link rel="stylesheet" href="{{ url('/') }}/css/w3.css"> 
<link rel="stylesheet" href="{{ url('/') }}/css/iziToast.css">
<link rel="stylesheet" href="{{ url('/') }}/css/macwindow.css">
<link rel="stylesheet" href="{{ url('/') }}/css/switch.css">
<link rel="stylesheet" href="{{ url('/') }}/css/app.css">

<!-- export tools -->
<!-- print library -->
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">


<!-- Google Font 
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
-->
 
<!-- jQuery 3 -->
<script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>


@include("template.commen")  
<script>
    var public_path = '{{ url("/") }}';
</script>

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

    .modal-backdrop {
        display: none;
    }

    .modal { 
    }
    
    .box, .nav-tabs-custom, .shadow {
        box-shadow: 0 1px 1px 0 rgba(0,0,0,0.14), 0 1px 1px -2px rgba(0,0,0,0.12), 0 1px 2px 0 rgba(0,0,0,0.4)!important;
    }
    
    .shadow-0 {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }
    
    .select2-dropdown, .select2-dropdown--below {
        z-index: 99999999999999;
    }
    
    .w3-col {
        float: right
    }
    
    .treeview-menu {
        padding-right: 35px;
    }
    
    .cursor {
        cursor: pointer;
    }
    
    .w3-text-doctoraak {
        color: #8659fb;
    }
    
    .w3-text-dark-doctoraak {
        color: #683bfc;
    }
    
    .w3-doctoraak {
        background-color: #8659fb!important;
        color: white!important;
    }
    
    .w3-dark-doctoraak {
        background-color: #683bfc!important;
        color: white!important;
    }
    
    .dt-button   {
        background-image: none!important;
        background-color: #683bfc!important;
        color: white!important;
    }
    
    .dt-button:hover   {
        background-image: none!important;
        background-color: #8659fb!important;
        color: white!important;
    }
    
    .select2 {
        width: 100%!important;
    }
  
    tr:hover {
         /* background-color: #fafafa;*/
        cursor: pointer;
    }
     
</style>