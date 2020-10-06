@extends("layout.app", ["title" => __('translation')])


@section("breadcrumb")
<div class="w3-padding" style="padding-bottom: 0px" >
    <ol class="breadcrumb shadow w3-round w3-white">
        <li><a href="#" onclick="showPage('dashboard')" >{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('translation') }}</li>
    </ol>
</div>

@endsection

@section("boxHeader") 
@endsection

@section("content")

<table class="table" id="table" >
    <thead>
        <tr class="w3-dark-gray" >  
            <th>{{ __('key') }}</th>   
            <th>{{ __('word in English') }}</th>
            <th>{{ __('word in Arabic') }}</th>
        </tr>
    </thead>
    <tbody> 
        @foreach(App\Translation::all() as $item)
        <tr class="dictionary-item" data-id="{{ $item->id }}" >
            <td>{{ $item->key }}</td>
            <td> 
                <input  
                    type="text" 
                    class="w3-input w3-block  word_en w3-border-gray w3-round"   
                    value="{{ $item->word_en }}"
                    style="width: 100%;border: 1px solid"
                    placeholder="">
            </td>
            <td>
                <input  
                    type="text" 
                    class="w3-input w3-block  word_ar w3-border-gray w3-round"   
                    value="{{ $item->word_ar }}"
                    style="width: 100%;border: 1px solid"
                    placeholder="">
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 
<br>
<div class="form-group w3-padding ">
    <button class="btn w3-indigo shadow btn-sm" onclick="editTranslation(this)" >
        <i class="fa fa-check" ></i> {{ __('save') }}
    </button>
</div>
@endsection 

@section("scripts")
<script>

    function editTranslation(button) {
        $(button).attr('disabled', 'disabled');
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');

        var translations = [];

        $(".dictionary-item").each(function () {
            var item = {};
            item.id = $(this).attr('data-id');
            item.word_en = $(this).find(".word_en").val();
            item.word_ar = $(this).find(".word_ar").val();

            translations.push(item);
        });

        var data = {
            translations: JSON.stringify(translations),
            _token: '{{ csrf_token() }}'
        };

        $.post('{{ url("/setting/translation/update?") }}', $.param(data), function (r) {
            if (r.status == 1) {
                success(r.message);
            } else {
                error(r.message);
            }
            $(button).removeAttr("disabled");
            $(button).html(' <i class="fa fa-check" ></i> {{ __('save') }}');
        });
    }

</script>
@endsection
