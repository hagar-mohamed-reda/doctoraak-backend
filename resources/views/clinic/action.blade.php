<div style="width:200px" >
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/clinic/edit') . '/' . $clinic->id }}')" ></i>
 
    <i class="fa fa-desktop w3-text-green w3-button" onclick="showPage('clinic/show/' + '{{ $clinic->id }}')" ></i>
   
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/clinic/remove/') .'/' . $clinic->id }}')" ></i>
     
</div>