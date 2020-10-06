@extends("layout.app", ["title" => __('userinsurances')])


@php
$builder = (new App\UserInsurance())->getViewBuilder();
@endphp

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('userinsurance') }}</li>
    </ol>
</div>

<div class="w3-padding" >
    <div class="box box-widget "  id="filter" >
        <div class="box-header with-border">

            <div class="user-block w3-left"> 
                <span class="username"><i class="fa fa-filter" ></i> {{ __('filter') }}</span>
            </div>

            <!-- /.user-block -->
            <div class="box-tools"> 
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    <div class="ripple-container"></div>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>

        <div class="box-body" >
            <br>
            <div class="w3-row" >
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('search with userinsurance info') }}</label>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('insurance') }}</label>
                    <select class="form-control" v-model="filter.insurance_id"  > 
                        @foreach(App\Insurance::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                 

                <div class="w3-col l3 m4 s6 w3-padding" >
                    <label> </label> 
                    <br>
                    <button class="btn btn-success btn-flat" onclick="search()" >{{ __('search') }}</button>
                    <button class="btn btn-warning btn-flat" onclick="showAll()" >{{ __('show all') }}</button>
                </div>
            </div>
        </div>
    </div>
</div> 

@endsection

@section("boxHeader")
<button class="btn btn-primary btn-flat modal-trigger"
        data-toggle="modal"
        data-target="#addModal"  >{{ __('add userinsurance') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" >  
            <th>{{ __('name') }}</th> 
            <th>{{ __('name_ar') }}</th> 
            <th>{{ __('name_fr') }}</th> 
            <th>{{ __('insurance') }}</th> 
            <th>{{ __('email') }}</th> 
            <th>{{ __('password') }}</th> 
            <th></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table> 
@endsection

@section('section')
<!-- add modal -->
<div class="modal fade"   role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('add userinsurance') }}</center>
            </div>
            <div class="modal-body">
                {!! $builder->loadAddView() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('edit userinsurance') }}</center>
            </div>
            <div class="modal-body editModalPlace">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("scripts")
<script>
    var table = null;
    var hiddenFields = [];
    var filterRate = null;
    
    function search() {
        var url = "{{ url('/') }}/userinsurance/data?" + $.param(filter.filter);
                             
        table.ajax.url(url);
        table.ajax.reload();
    }

    function showAll() {
        filter.filter = {}; 
        search();
    }
 
    function reload() {
        //
    }

    var filter = new Vue({
        el: '#filter',
        data: {
            filter: {
            }
        },
        methods: {
        },
        computed: {
        },
        watch: {
        }
    });
     
    $(document).ready(function () {
        table = $('#table').DataTable({
            "processing": true,
            "order": [[0, "desc"]],
            "serverSide": true,
            "pageLength": 5,
            "autoWidth": false,
            "ajax": "{{ route('userinsuranceData') }}",
            "columns": [ 
                {"data": "name"},
                {"data": "name_ar"},
                {"data": "name_fr"},
                {"data": "insurance_id"},
                {"data": "email"},
                {"data": "password"},
                {"data": "action"}
            ]
        }); 
    
        @if (request()->action == 'create')
            $('#addModal').modal('show');
        @endif
 
    
        init();
    });
    
    
    function init() {
        writeNames();
    }

    

</script>
@endsection
