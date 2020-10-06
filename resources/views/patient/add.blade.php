@extends("layout.app", ["title" => "المرضى"])



@section("boxHeader")
<button class="btn btn-primary btn-flat" onclick="toggleCol(table, hiddenFields)" >عرض مختصر البيانات</button> 
@endsection

@section("content")
 
<form>
    <table class="table" id="table" > 
        <tr>
            <th>

            </th>
            <td>

            </td>
        </tr>
    </table>
</form>
@endsection

@section("scripts")
<script>

</script>
@endsection
