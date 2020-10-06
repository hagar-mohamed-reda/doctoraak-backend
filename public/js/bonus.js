/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var counter = 1;
var savedCounter = 1;

function setSelect2() {
    //Initialize Select2 Elements
    $('.select2').select2();
}

function loadData() {
    $.get(url + "/bonus/data", function (data) {
        $("#tableContainer").html(data);
        //
        dataTable();
    });
}


function editBonus(bonus) {
    $(".editModal").html('');
    $(".editModal").show();
    $.get(url + "/bonus/edit/" + bonus, function (data) {
        $(".editModal").html(data); 
        //
        setNicescroll();
    });
}

function updateBonus(id) {
    var bonus = {};
    
    bonus.employee = id;
    bonus.c1 = $(".editModal .c1").val();
    bonus.c2 = $(".editModal .c2").val();
    bonus.c3 = $(".editModal .c3").val();
    bonus.c4 = $(".editModal .c4").val();
    bonus.c5 = $(".editModal .c5").val();
    bonus.c6 = $(".editModal .c6").val();
    bonus.c7 = $(".editModal .c7").val();
    bonus.c8 = $(".editModal .c8").val();
    bonus.c9 = $(".editModal .c9").val();
    bonus.c10 = $(".editModal .c10").val();
    bonus.c11 = $(".editModal .c11").val();
    bonus.c12 = $(".editModal .c12").val();
    bonus.c13 = $(".editModal .c13").val();
    bonus.c14 = $(".editModal .c14").val();
    bonus.c15 = $(".editModal .c15").val();
    bonus.c16 = $(".editModal .c16").val();
    bonus.c17 = $(".editModal .c17").val();
    bonus.c18 = $(".editModal .c18").val();
    bonus.c19 = $(".editModal .c19").val();
     
    sendBonus(bonus, function(){
        $(".editModal").hide();
    }, '/bonus/update/'+id);
     
}

//function addBonus(form, action) {
//
//    $.post(form.action,
//            "name=" + form.name.value + "&start=" + form.start.value + "&end=" + form.end.value + "&_token=" + form._token.value,
//            function (data) {
//                var r = JSON.parse(data);
//
//                if (r.status == "1") {
//                    // show message
//                    success(r.message);
//                    // reset form
//                    form.reset();
//                    // load new data
//                    loadData();
//
//                    if (action != null)
//                        action();
//                }
//                else
//                    error(r.message);
//            });
//
//    return false;
//}

function onfinished() {
    $(".loader").slideUp(100);
    $(".dataSection").slideDown(500);
    $(".modal-footer").show();
    $(".notmain").remove();

    // reset inputs 
    resetTableInputs();
    
    // reload window
    window.location.reload();
}

function addData(bonuss) {
    if (bonuss.length <= 0) {
        onfinished();
        return;
    }
    var bonus = bonuss.pop();
    sendBonus(bonus, function () {
        $("#savedNumber").text(savedCounter);

        savedCounter++;
        addData(bonuss);
    }, '/bonus/store');
}

function addBonus() {
    var bonuss = collectData();
    $(".dataSection").slideUp();
    $(".modal-footer").hide();
    $(".loader").slideDown(200);

    //
    $("#tatalNumber").html(bonuss.length);
    savedCounter = 1;

    addData(bonuss);
}

function sendBonus(bonus, action, path) {
    // _token
    var _token = $("input[name=_token]").val();


    $.post(public_path + path, "_token="+_token+"&bonus="+JSON.stringify(bonus), function (data) {

            var r = data;
            try {
                r = JSON.parse(data);
            }catch(e) {
                error(r);
            }

            if (r.status == "1") {
                // show message
                success(r.message);

                // load new data
                loadData();

            }
            else
                error(r.message);

            if (action != null)
                action(data);
            $(".response")[0].innerHTML += r.message + "<br>";
        });

    return false;
}

function collectData() {
    var bonuss = [];
    $(".rows tr").each(function () {
        var bonus = {};
        bonus.employee = $(this).find(".employee").val();
        bonus.c1 = $(this).find(".c1").val();
        bonus.c2 = $(this).find(".c2").val();
        bonus.c3 = $(this).find(".c3").val();
        bonus.c4 = $(this).find(".c4").val();
        bonus.c5 = $(this).find(".c5").val();
        bonus.c6 = $(this).find(".c6").val();
        bonus.c7 = $(this).find(".c7").val();
        bonus.c8 = $(this).find(".c8").val();
        bonus.c9 = $(this).find(".c9").val();
        bonus.c10 = $(this).find(".c10").val();
        bonus.c11 = $(this).find(".c11").val();
        bonus.c12 = $(this).find(".c12").val();
        bonus.c13 = $(this).find(".c13").val();
        bonus.c14 = $(this).find(".c14").val();
        bonus.c15 = $(this).find(".c15").val();
        bonus.c16 = $(this).find(".c16").val();
        bonus.c17 = $(this).find(".c17").val();
        bonus.c18 = $(this).find(".c18").val();
        bonus.c19 = $(this).find(".c19").val();


        bonuss.push(bonus);
    });


    return bonuss;
}

function validate(input) {
    if ($(input).is(":invalid")) {
        error(ERROR_INPUT_VALUE);
        $("#addBtn").hide();
    } else
        $("#addBtn").show();
}

function addRow() {
    // create obj row
    var row = document.createElement("tr");
    row.className = "notmain row" + counter;

    // fill the html obj
    row.innerHTML = $(".row1").html();

    // select input
    var selectInput = $(".hiddenSection").html();

    $(row).find(".employeeCol").html(selectInput);

    $(row).find(".employee").select2();

    // add row to the table
    $(".rows").append(row);


    // increase counter
    counter++;
}

function removeRow(div) {
    swal({
        title: "😧 Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            if ($(".addTable tr").length <= 2)
                error("you should let at least one row");
            else {
                $(div).parent().parent().remove();
                success("row removed");
                counter--;
            }
        } else {
        }
    });
}

function validListner() {
    setInterval(function () {
        var valid = true;
        $(".bonusModal .form-control").each(function () {
            if ($(this).is(":invalid"))
                valid = false;
        });

        valid ? $("#addBtn").show() : $("#addBtn").hide();
    }, 1000);
}

function resetTableInputs() {
    $(".addTable .form-control").val('');
}


$(document).ready(function () {
    // load bonus date
    loadData();

    //Initialize Select2 Elements
    setSelect2();

    // valid on inputs
    validListner();

    // reset all inputs of table
    resetTableInputs();
});
//****************************************
// calculating section
//****************************************

function calculateCols(input) {
    var tr = $(input).parent().parent()[0];
    calculateC10(tr);
    calculateC11(tr);
    calculateC12(tr);
    calculateC13(tr);
    calculateC14(tr);
    calculateC16(tr);
    calculateC17(tr);
    calculateC18(tr);
    calculateC19(tr);
}

function calculateC10(tr) {
    var c1 = $(tr).find(".c1")[0];
    var c2 = $(tr).find(".c2")[0];
    var c3 = $(tr).find(".c3")[0];
    var c4 = $(tr).find(".c4")[0];
    var c5 = $(tr).find(".c5")[0];
    var c6 = $(tr).find(".c6")[0];
    var c7 = $(tr).find(".c7")[0];
    var c8 = $(tr).find(".c8")[0];
    var c9 = $(tr).find(".c9")[0];

    var c10 = $(tr).find(".c10")[0];

    var value =
            parseFloat(c1.value) + parseFloat(c2.value) + parseFloat(c3.value) +
            parseFloat(c4.value) + parseFloat(c5.value) + parseFloat(c6.value) +
            parseFloat(c7.value) + parseFloat(c8.value) + parseFloat(c9.value);

    c10.value = value.toFixed(2);
}

function calculateC11(tr) {
    var c1 = $(tr).find(".c1")[0];
    var c2 = $(tr).find(".c2")[0];
    var c3 = $(tr).find(".c3")[0];
    var c4 = $(tr).find(".c4")[0];
    var c5 = $(tr).find(".c5")[0];
    // persent
    var c11p = $(tr).find(".c11p")[0];

    var c11 = $(tr).find(".c11")[0];

    var value =
            parseFloat(c1.value) + parseFloat(c2.value) + parseFloat(c3.value) +
            parseFloat(c4.value) + parseFloat(c5.value);

    value *= parseFloat(c11p.value) / 100;

    c11.value = value.toFixed(2);
}

function calculateC12(tr) {
    var c7 = $(tr).find(".c7")[0];
    var c8 = $(tr).find(".c8")[0];

    var c12p = $(tr).find(".c12p")[0];

    var c12 = $(tr).find(".c12")[0];

    var value =
            parseFloat(c7.value) + parseFloat(c8.value);

    value *= parseFloat(c12p.value) / 100;


    c12.value = value.toFixed(2);
}

function calculateC13(tr) {
    var c11 = $(tr).find(".c11")[0];
    var c12 = $(tr).find(".c12")[0];


    var c13 = $(tr).find(".c13")[0];

    var value =
            parseFloat(c11.value) + parseFloat(c12.value);

    c13.value = value.toFixed(2);
}

function calculateC14(tr) {
    var c10 = $(tr).find(".c10")[0];
    var c13 = $(tr).find(".c13")[0];


    var c14 = $(tr).find(".c14")[0];

    var value =
            parseFloat(c10.value) - parseFloat(c13.value);

    c14.value = value.toFixed(2);
}

function calculateC16(tr) {
    var c15 = $(tr).find(".c15")[0];
    var c14 = $(tr).find(".c14")[0];


    var c16 = $(tr).find(".c16")[0];

    var value =
            parseFloat(c14.value) + parseFloat(c15.value);

    c16.value = value.toFixed(2);
}

function calculateC17(tr) {
    var c1 = $(tr).find(".c1")[0];
    var c2 = $(tr).find(".c2")[0];
    var c3 = $(tr).find(".c3")[0];
    var c4 = $(tr).find(".c4")[0];
    var c5 = $(tr).find(".c5")[0];

    var c17 = $(tr).find(".c17")[0];

    var value =
            parseFloat(c1.value) + parseFloat(c2.value) +
            parseFloat(c3.value) + parseFloat(c4.value) +
            parseFloat(c5.value);

    value *= 0.26;

    c17.value = value.toFixed(2);
}

function calculateC18(tr) {
    var c7 = $(tr).find(".c7")[0];
    var c8 = $(tr).find(".c8")[0];

    var c18 = $(tr).find(".c18")[0];

    var value =
            parseFloat(c7.value) + parseFloat(c8.value);

    value *= 0.24;

    c18.value = value.toFixed(2);
}

function calculateC19(tr) {
    var c17 = $(tr).find(".c17")[0];
    var c18 = $(tr).find(".c18")[0];

    var c19 = $(tr).find(".c19")[0];

    var value =
            parseFloat(c17.value) + parseFloat(c18.value);

    c19.value = value.toFixed(2);
}