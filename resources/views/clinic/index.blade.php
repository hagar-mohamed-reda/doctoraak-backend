@extends("layout.app", ["title" => "العيادات"])


@php
$builder = (new App\Clinic())->getViewBuilder();
@endphp


@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('clinic') }}</li>
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
                    <label>{{ __('search with clinic info') }}</label>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div> 
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('search with fees') }}</label>
                    <input type="number" class="form-control"  v-model="filter.fees"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('search with fees2') }}</label>
                    <input type="number" class="form-control"  v-model="filter.fees2"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('search with waiting time') }}</label>
                    <input type="number" class="form-control"  v-model="filter.waiting_time"  > 
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
                    <label>{{ __('city') }}</label>
                    <select class="form-control select2"  onchange="filter.filter.city_id = this.value"  > 
                        <option value="" >{{ __('all') }}</option>
                        @foreach(App\City::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('area') }}</label>
                    <select class="form-control" v-model="filter.area_id"  > 
                        @foreach(App\Area::all() as $item)
                        <option value="{{ $item->id }}" v-if="filter.city_id=='{{ $item->city_id }}'" >{{ $item->name_ar }}</option>
                        @endforeach
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
                    <label>{{ __('availability') }}</label>
                    <select class="form-control" has-select2="off" v-model="filter.availability"  > 
                        <option value="1"  >{{ __('available') }}</option>
                        <option value="2"  >{{ __('not available') }}</option>
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
        data-target="#addModal"  >{{ __('add clinic') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" > 
            <th></th> 
            <th></th> 
            <th>{{ __('phone') }}</th> 
            <th>{{ __('address') }}</th>
            <th>{{ __('waiting_time') }}</th>
            <th>{{ __('doctor') }}</th>
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
    <div class="modal-dialog modal-" role="document" >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('add clinic') }}</center>
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
                <center class="modal-title w3-xlarge">{{ __('edit clinic') }}</center>
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

    function search() {
        var url = "{{ url('/') }}/clinic/data?" + $.param(filter.filter);

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
        table = $('#table').DataTable({
            "processing": true,
            "pageLength": 5,
            "autoWidth": false,
            "serverSide": true,
            "ajax": "{{ route('clinicData') }}",
            "columns": [
                {"data": "availability"},
                {"data": "photo"},
                {"data": "phone"},
                {"data": "address"},
                {"data": "waiting_time"},
                {"data": "doctor_id"},
                {"data": "active"},
                {"data": "action"}
            ]
        });


        $('.select2').select2();
        loadAreas();
        
    });
    
    function loadAreas() {
        $('select[name=city_id]').each(function () {
            $(this.form.area_id).find('option').hide();

            $(this).change(function () {
                $(this.form.area_id).find('option[data-id=' + this.value + ']').show();
            });
        });
    }
    
    function init() { 
        loadAreas();
    }
    

</script>
@endsection
