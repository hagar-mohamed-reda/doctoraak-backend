<div style="width: 200px" >  
    @if ($clinicOrder->active == 1)
    <i class="btn w3-text-red fa fa-info-circle"
       data-toggle="modal"
       data-target="#reservationModal{{ $clinicOrder->id }}" > {{ __('cancel reservation') }} </i> 

    @endif
    <!--
   <a href="#" onclick="sendUrl('{{ url('/') }}/clinicorder/active/{{ $clinicOrder->id }}', null, this)" class="btn btn-success btn-flat btn-sm" >
       <i class="fa fa-info-circle" ></i> <span>تفعيل</span>
   </a>
    
    --> 
</div>


<!-- add modal -->
<div class="modal fade"   role="dialog" id="reservationModal{{ $clinicOrder->id }}" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-sm" role="document" >
        <div class="modal-content ">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <center class="modal-title w3-xlarge">{{ __('cancel reservation number') }} {{ $clinicOrder->id }}</center>
            </div>
            <div class="modal-body">
                <form class="form" method="post" action="{{ url('api/patient/cancel-order') }}" >
                    <center>
                    <input type="hidden" name="patient_id" value="{{ $clinicOrder->patient_id }}" >
                    <input type="hidden" name="api_token" value="{{ optional($clinicOrder->patient)->api_token }}" >
                    <input type="hidden" name="order_id" value="{{ $clinicOrder->id }}" >
                    <input type="hidden" name="order_type" value="DOCTOR" >

                    <div class="form-group w3-padding" >
                        <label>{{ __('tell me why') }}</label>
                        <br>
                        <textarea class="form-control" name="message" required="" ></textarea>
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="btn w3-dark-doctoraak" >{{ __('submit') }}</button>
                    </div>
                    </center>
                </form>

            </div>
            <div class="modal-footer" >
                <center>
                    <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Close">{{ __('close') }}</button>
                </center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    formAjax();
</script>
