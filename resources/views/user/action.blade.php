<div style="width:200px" >
      <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/user/edit') . '/' . $user->id }}')" ></i>
 
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/user/remove/') .'/' . $user->id }}')" ></i>
     
</div>