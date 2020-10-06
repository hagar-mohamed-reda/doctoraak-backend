@extends("layout.app", ["title" => "العيادات"])


@php
$builder = (new App\Lab())->getViewBuilder();
@endphp


@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('lab') }}</li>
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
                    <label>{{ __('search with doctor info') }}</label>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('insurance companies') }}</label>
                    <select class="form-control select2 filter_insurance_id" multiple=""  onchange="filter.filter.insurance_id=$('.filter_insurance_id').val()"  >
                        <!--
                        <option value=""  >{{ __('all') }}</option>
                        -->
                        @foreach(App\Insurance::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('lab_doctor') }}</label>
                    <select class="form-control" v-model="filter.lab_doctor_id"  >
                        <!--
                        <option value=""  >{{ __('all') }}</option>
                        -->
                        @foreach(App\LabDoctor::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('city') }}</label>
                    <select class="form-control" v-model="filter.city_id"  > 
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
                    <label>{{ __('delivery') }}</label>
                    <select class="form-control" has-select2="off" v-model="filter.delivery"  > 
                        <option value="1"  >{{ __('on') }}</option>
                        <option value="2"  >{{ __('off') }}</option>
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
        data-target="#addModal"  >{{ __('add lab') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>  
        <tr class="w3-dark-gray" > 
            <th></th>   
            <th>{{ __('name') }}</th> 
            <th>{{ __('phone') }}</th> 
            <th>{{ __('address') }}</th> 
            <th>{{ __('doctor') }}</th> 
            <th>{{ __('insurance') }}</th> 
            <th>{{ __('active') }}</th>
            <th>{{ __('delivery') }}</th> 
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
                <center class="modal-title w3-xlarge">{{ __('add lab') }}</center>
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
                <center class="modal-title w3-xlarge">{{ __('edit lab') }}</center>
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
        var url = "{{ url('/') }}/lab/data?" + $.param(filter.filter);

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
            "ajax": "{{ route('labData') }}",
            "columns": [
                {"data": "photo"},
                {"data": "name"},
                {"data": "phone"},
                {"data": "address"},   
                {"data": "lab_doctor_id"},
                {"data": "insurance"},
                {"data": "active"},
                {"data": "delivery"},
                {"data": "action"}
            ]
        });


        $('.select2').select2();
        loadAreas();
        
        @if (request()->action == 'create')
            $('#addModal').modal('show');
        @endif
 
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
        $('.select2').select2();
    }
    

</script>
@endsection
