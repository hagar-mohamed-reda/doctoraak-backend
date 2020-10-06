<form class="form" method="post" action="{{ url('/radiology/working_hours/update/') }}/{{ $radiology->id }}"  >
    @csrf
    <table class="w3-table table-bordered text-center" >
        <tr class="w3-dark-gray" >
            <th>{{ __('day') }}</th> 
            <th   >{{ __('active') }}</th> 
            <th  >{{ __('part_from') }}</th> 
            <th   >{{ __('part_to') }}</th>  
        </tr>
        
        @if ($radiology->working_hours()->count() <= 0)
        @for($index = 0; $index < 7; $index ++) 
        @php
            $item = new App\LabWorkingHours;
            $item->id = $index;
            $item->active = 0;
        @endphp
        <tr  class=""  >
            <td>
                <input name="day[]" type="hidden" value="{{ $index + 1 }}"  >
                {{ App\WorkingHours::getDayName($index + 1) }}
            </td> 
            <td>  
                    <input type="hidden" name="active[]" id="editInputActive{{ $radiology->id }}{{ $item->id }}" value="{{ $item->active }}">
                    
                    <input type="checkbox" 
                           class="shadow hidden" 
                           id="editActive{{ $radiology->id }}{{ $item->id }}" 
                           placeholder="active"  
                           {{ $item->active==1? 'checked' : '' }}
                           onclick="this.checked ? $('#editInputActive{{ $radiology->id }}{{ $item->id }}').val(1) : $('#editInputActive{{ $radiology->id }}{{ $item->id }}').val(0)">
                    <radiologyel for="editActive{{ $radiology->id }}{{ $item->id }}" class="switch "></radiologyel>
                
            </td>
            <td  >
                <input type="time" name="part_from[]" value="{{ $item->part_from }}" class="form-control input-sm" >
            </td> 
            <td  >
                <input type="time" name="part_to[]" value="{{ $item->part_to }}" class="form-control input-sm" >
            </td> 
        </tr> 
        @endfor
        @endif
        
        @foreach($radiology->working_hours()->get() as $item) 
        <tr  class=""  >
            <td>
                <input name="day[]" type="hidden" value="{{ $item->day }}"  >
                {{ App\WorkingHours::getDayName($item->day) }}
            </td> 
            <td> 
                    <input type="hidden" name="active[]" id="editInputActive{{ $radiology->id }}{{ $item->id }}" value="{{ $item->active }}">
                    
                    <input type="checkbox" 
                           class="shadow hidden" 
                           id="editActive{{ $radiology->id }}{{ $item->id }}" 
                           placeholder="active"  
                           {{ $item->active==1? 'checked' : '' }}
                           onclick="this.checked ? $('#editInputActive{{ $radiology->id }}{{ $item->id }}').val(1) : $('#editInputActive{{ $radiology->id }}{{ $item->id }}').val(0)">
                    <radiologyel for="editActive{{ $radiology->id }}{{ $item->id }}" class="switch "></radiologyel>
                </div>  
            <td  >
                <input type="time" name="part_from[]" value="{{ $item->part_from }}" class="form-control input-sm" >
            </td> 
            <td  >
                <input type="time" name="part_to[]" value="{{ $item->part_to }}" class="form-control input-sm" >
            </td> 
        </tr> 
        @endforeach
    </table>
    <br>
    <button class="btn w3-dark-doctoraak w3-block" type="submit" >{{ __('save') }}</button>
</form>