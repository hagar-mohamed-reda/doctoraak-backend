/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setTimePicker() { 
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
}

function loadData() {
    $.get(url + "/category/data", function (data) {
        $("#tableContainer").html(data);
        //
        dataTable();
    });
}


function editCategory(category) {
    $(".editModal").html('');
    $(".editModal").show();
    $.get(url + "/category/edit/" + category, function (data) {
        $(".editModal").html(data);
        
        // set timepicker for html
        setTimePicker();
    });
}

function addCategory(form, action) {

    $.post(form.action,
            "name=" + form.name.value + 
            "&start=" + form.start.value + 
            "&end=" + form.end.value + 
            "&holiday_start=" + form.holiday_start.value +  
            "&holiday_end=" + form.holiday_end.value +  
            "&_token=" + form._token.value,
            function (data) {
                var r = JSON.parse(data);

                if (r.status == "1") {
                    // show message
                    success(r.message);
                    // reset form
                    form.reset();
                    // load new data
                    loadData();

                    if (action != null)
                        action();
                }
                else {
                    error(r.message);
                    form.reset();
                }
            });

    return false;
}


$(document).ready(function () {
    // load category date
    loadData();
    
    // set timepicker plugin
    setTimePicker();
});