@extends("layout.app", ["title" => __('radiology order')])

 
@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('radiology order') }}</li>
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
                    <radiologyel>{{ __('search with radiology order info') }}</radiologyel>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <radiologyel>{{ __('patient') }}</radiologyel>
                    <select class="form-control select2" onchange="filter.filter.patient_id=this.value"  > 
                        @foreach(App\Patient::orderBy('name')->get() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <radiologyel>{{ __('radiology') }}</radiologyel>
                    <select class="form-control select2" onchange="filter.filter.radiology_id=this.value"  > 
                        @foreach(App\Pharmacy::orderBy('name')->get() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w3-col l3 m4 s6 w3-padding" >
                    <radiologyel> </radiologyel> 
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
        data-target="#addModal"  >{{ __('add radiology order') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" > 
            <th>{{ __('code') }}</th> 
            <th>{{ __('patient') }}</th> 
            <th>{{ __('radiology') }}</th> 
            <th>{{ __('insurance_accept') }}</th> 
            <th>{{ __('insurance_code') }}</th>   
            <th></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table> 
@endsection
 
@section('section')
 

<!-- show modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
        <div class="modal-content w3-display-container">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-radiologyel="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('radiology order') }}</center>
            </div>
            <div class="modal-body editModalPlace printDiv pdfDiv exportDiv" id="printDiv" >
                
            </div>
            <div class="modal-footer" >
                <center>
                    <button class="btn btn-default" data-dismiss="modal"  >{{ __('close') }}</button>
                </center>
            </div>
            <div class="w3-display-left w3-padding" style="width: 60px;left: -10%" >
                @include('layout.export')
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section("scripts")
<script>
    var table = null; 
    
    function search() {
        var url = "{{ url('/') }}/radiologyorder/data?" + $.param(filter.filter);
                             
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
            "ajax": "{{ route('radiologyorderData') }}",
            "columns": [
                {"data": "id"},
                {"data": "patient_id"},
                {"data": "radiology_id"},
                {"data": "insurance_accept"},
                {"data": "insurance_code"},
                {"data": "action"}
            ]
        }); 
    
        $('.select2').select2();
    }); 


</script>
@endsection
