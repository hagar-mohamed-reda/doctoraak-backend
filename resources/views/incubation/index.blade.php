@extends("layout.app", ["title" => "الحضانات"])


@php
$builder = (new App\Incubation())->getViewBuilder();
@endphp

@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('incubation') }}</li>
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
                    <label>{{ __('search with incubation info') }}</label>
                    <input type="search" class="form-control"  v-model="filter.search_string"  > 
                </div>
                <div class="w3-col l3 m4 s6 w3-padding" > 
                    <label>{{ __('search with bed number') }}</label>
                    <input type="number" class="form-control"  v-model="filter.bed_number"  > 
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
                    <label for="rate">rate</label>  
                    <input type="hidden" name="rate" id="rate">
                    <div class="" id="filterRate">
                    
                    </div>  
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
        data-target="#addModal"  >{{ __('add incubation') }}</button> 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" > 
            <th>{{ __('name') }}</th> 
            <th>{{ __('bed_number') }}</th> 
            <th>{{ __('rate') }}</th> 
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
                <center class="modal-title w3-xlarge">{{ __('add incubation') }}</center>
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
                <center class="modal-title w3-xlarge">{{ __('edit incubation') }}</center>
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
        var url = "{{ url('/') }}/incubation/data?" + $.param(filter.filter);
                             
        table.ajax.url(url);
        table.ajax.reload();
    }

    function showAll() {
        filter.filter = {};
        filterRate.rate(0);
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
            "ajax": "{{ route('incubationData') }}",
            "columns": [
                {"data": "name"},
                {"data": "bed_number"},
                {"data": "rate"},
                {"data": "action"}
            ]
        });
        loadAreas();
        
        filterRate = new Ratebar(document.getElementById('filterRate'));
        filterRate.setOnRate(function(){
            filter.filter.rate = filterRate.value;
        });   
    
    });

    function loadAreas() {
        $('select[name=city_id]').each(function () {
            $(this.form.area_id).find('option').hide();

            $(this).change(function () {
                $(this.form.area_id).find('option[data-id=' + this.value + ']').show();
            });
        });
    }


</script>
@endsection
