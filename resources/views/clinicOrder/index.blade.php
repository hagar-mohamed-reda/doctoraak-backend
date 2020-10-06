@extends("layout.app", ["title" => __('reservations')])
 

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('reservations') }}</li>
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
                    <label>{{ __('orders info') }}</label>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('date_from') }}</label>
                    <input type="date" class="form-control"  v-model="filter.date_from"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('date_to') }}</label>
                    <input type="date" class="form-control"  v-model="filter.date_to"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('reservation_number') }}</label>
                    <input type="number" class="form-control"  v-model="filter.reservation_number"  > 
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('doctor') }}</label>
                    <select class="form-control select2" onchange="filter.filter.doctor_id = this.value"  > 
                        <option value="" >{{ __('all') }}</option>
                        @foreach(App\Doctor::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('patient') }}</label>
                    <select class="form-control select2" onchange="filter.filter.patient_id = this.value"  > 
                        <option value="" >{{ __('all') }}</option>
                        @foreach(App\Patient::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" >
                    <label>{{ __('part') }}</label>
                    <select class="form-control"  v-model="filter.part_id"  > 
                        <option value="1"  >{{ __('part 1') }}</option>
                        <option value="2"  >{{ __('part 2') }}</option>
                    </select>
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" >
                    <label>{{ __('type') }}</label>
                    <select class="form-control"  v-model="filter.type"  > 
                        <option value="1"  >{{ __('reservation') }}</option>
                        <option value="2"  >{{ __('consultation') }}</option>
                        <option value="3"  >{{ __('continue') }}</option> 
                    </select>
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" >
                    <label>{{ __('active') }}</label>
                    <select class="form-control" has-select2="off" v-model="filter.active"  > 
                        <option value="1"  >{{ __('active') }}</option>
                        <option value="2"  >{{ __('not active') }}</option>
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
        data-target="#addModal"  >{{ __('book a reservation') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" > 
            <th>{{ __('order code') }}</th>
            <th>{{ __('date') }}</th> 
            <th>{{ __('part') }}</th> 
            <th>{{ __('reservation_number') }}</th> 
            <th>{{ __('patient') }}</th>
            <th>{{ __('doctor') }}</th>
            <th>{{ __('notes') }}</th>
            <th>{{ __('type') }}</th>
            <th>{{ __('active') }}</th>
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
    <div class="modal-dialog modal-lg" role="document"  >
        <div class="modal-content " style="width: 90%!important;" >
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('select patient') }}</center>
            </div>
            <div class="modal-body table-responsive">
                <table class="table" id="patientTable" >
                <thead>
                    <tr class="w3-dark-gray" >
                        <th>{{ __('') }}</th> 
                        <th>{{ __('name') }}</th> 
                        <th>{{ __('phone') }}</th> 
                        <th>{{ __('insurance_company') }}</th> 
                        <th>{{ __('active') }}</th> 
                        <th>{{ __('insurance_status') }}</th> 
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div><!-- /.modal-content -->
            <div class="modal-footer" >
                <center>
                    <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Close">{{ __('close') }}</button>
                </center>
            </div>
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 
@endsection

@section("scripts")
<script>
    var table = null;
    var hiddenFields = [];
    
    

    function search() {
        var url = "{{ url('/') }}/clinicorder/data?" + $.param(filter.filter);

        table.ajax.url(url);
        table.ajax.reload();
    }

    function showAll() {
        filter.filter = {};
        $('.select2').val('');
        $(".select2").select2({
            allowClear: true
        });
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
        
        $('#patientTable').DataTable({
            "serverSide": true,
            "pageLength": 5,
            "autoWidth": false,
            "ajax": "{{ route('patientData') }}",
            "columns": [
                {"data": "photo"},
                {"data": "name"},
                {"data": "phone"},
                {"data": "insurance_id"},
                {"data": "active"},
                {"data": "insurance_status"},
                {"data": "action"}
            ]
        });
        
        table = $('#table').DataTable({
            "processing": true,
            "pageLength": 5,
            "autoWidth": false,
            "serverSide": true,
            "ajax": "{{ route('clinicorderData') }}",
            "columns": [
                {"data": "id"},
                {"data": "date"},
                {"data": "part_id"},
                {"data": "reservation_number"},
                {"data": "patient_id"},
                {"data": "doctor"},
                {"data": "notes"},
                {"data": "type"},
                {"data": "active"},
                {"data": "action"}
            ]
        });


        $('.select2').select2();
        loadAreas();
        
    });
     
    
    function init() { 
        //loadAreas();
    }
    

</script>
@endsection
