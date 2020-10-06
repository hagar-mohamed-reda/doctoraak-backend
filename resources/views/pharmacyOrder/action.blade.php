<div style="width:200px" >
 
    <i class="fa fa-desktop w3-text-green w3-button" onclick="edit('{{ url('/pharmacyorder/show') . '/' . $pharmacyorder->id }}')" ></i>
   
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/pharmacyorder/remove/') .'/' . $pharmacyorder->id }}')" ></i>
     
</div>