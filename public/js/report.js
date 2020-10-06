/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function load() {
    var div = document.createElement("div");
    $.get(public_path + "/report/", function (data) {
        div.innerHTML = data;

        $(".box").html($(div).find(".box").html());
        dataTable();
    });
}

function viewReport(report) {
    var myWindow = window.open("", "", "width=600,height=700");
    $.get(public_path + "/report/get/" + report, function (data) {
        myWindow.document.write(data);
    });
}

function removeReport(report) {
    remove('', url + '/report/remove/' + report, null, function () {
        load();
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

    dataTable();
});