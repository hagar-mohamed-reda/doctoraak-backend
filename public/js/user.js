/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function loadData(path) {
    $.get(url + "/user/data", function (data) {
        $("#tableContainer").html(data);
        //
        dataTable();
    });
}


function editUser(user) {
    $(".editModal").show();
    $.get(url + "/user/edit/" + user, function (data) {
        $(".editModal").html(data);
    });
}

function addUser(form, action) {

    $.post(form.action,
            "name=" + form.name.value + "&username=" + form.username.value + "&password=" + form.password.value + "&_token=" + form._token.value,
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
                else
                    error(r.message);
            });

    return false;
}

function addPrivilage(user, input) {
    if (input.checked) {
        $.get(public_path + "/privilage/add?user="+user+"&value=1&role="+input.getAttribute("data-role"), function(r){
            success(r);
        });
    } else {
        $.get(public_path + "/privilage/add?user="+user+"&value=0&role="+input.getAttribute("data-role"), function(r){
            success(r);
        });
    }
}


$(document).ready(function () {
    loadData();
});