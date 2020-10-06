/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function dataTable() {
    $('#table').DataTable({ 
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
//        "fnDrawCallback": function( oSettings ) {
//            this.clearTable();
//            
//          
//            console.log(this);
//        },
        paging: false, 
        searching: false,
        "order": [[0, "desc"]]
    });
}

function setTimePicker() {
    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    });
}

function loadData(page) {
    var search = $("#datatableSearch").val(); 
    $("#tableContainer").html("<center><span class='fa fa-spinner fa-spin w3-xxxlarge text-primary' ></span></center>");
    
    
    page = (page != undefined) ? page : 1;
    
    var path = "";
    if (search == undefined)
        path = url + "/daily/data?page=" + page;
    else
        path = url + "/daily/data?page=" + page + "&search=" + search;
    
    $.get(path, function (data) {
        $("#tableContainer").html(data);
        //
        dataTable();
    });
}

function editDaily(daily) {
    $(".editModal").html('');
    $(".editModal").show();
    $.get(url + "/daily/edit/" + daily, function (data) {
        $(".editModal").html(data);

        // set timepicker for html
        setTimePicker();
    });
}

function sendData(dailysQueue) {
    var daily = dailysQueue.shift();
    if (daily == undefined) {
        setTimeout(function () {
            window.location.reload();
        }, 2000);
        return;
    }
    // save to db
    addDaily(daily, function () {
        $("#savedNumber").text(daily.i);

        // loop
        sendData(dailysQueue);
    }, "/daily/store");
}

function addDailys() {
    $(".importTable").slideUp(300);
    $(".loader").slideDown(300);
    $("#tatalNumber").text((array.length + 1));

    var dailysQueue = [];
    for (var index = 1; index < array.length; index++) {
        var daily = {};
        daily.emp_id = array[index][$("#emp_id").attr("data-index")];
        daily.date = array[index][$("#date").attr("data-index")];
        daily.in_time = array[index][$("#in_time").attr("data-index")];
        daily.out_time = array[index][$("#out_time").attr("data-index")];
        daily.i = index;

        dailysQueue.push(daily);
    }

    sendData(dailysQueue);
}

function finish() {
    $(".importTable").slideDown(300);
    $(".loader").slideUp(300);
    //
    loadData();
}

function addDaily(daily, action, path) {
//    var patt = /(\d{2}\:\d{2})/g;
//    if (!patt.test(daily.in_time) || !patt.test(daily.out_time)) {
//        error(TIME_FORMAT);
//        finish();
//        return;
//    }

    var token = $("input[name=_token]").val();
    var d =
            "_token=" + token +
            "&emp_id=" + daily.emp_id +
            "&in_time=" + daily.in_time +
            "&out_time=" + daily.out_time +
            "&date=" + daily.date;
    $.post(
            public_path + path, d, function (data) {

                var r = JSON.parse(data);

                if (r.status == "1") {
                    // show message
                    success(r.message);

                    // load new data
                    //loadData();

                }
                else
                    error(r.message);

                if (action != null)
                    action(data);
                $(".response")[0].innerHTML += r.message + "<br>";
            }
    );


    return false;
}



function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var obj = document.getElementById(data);
    if (ev.target.id.indexOf("col") < 0) {
        if (ev.target.className.indexOf("column") > 0) {
            if ($(ev.target).find(".sheetCol").length > 0)
                return;
        }

        //console.log($(obj).attr('data-index'));
        $(ev.target).attr("data-index", $(obj).attr('data-index'));
        ev.target.appendChild(obj);
    }
}

var array = [];
function getFile(input) {
    var f = input.files[0];
    $("#filename").html(f.name);
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, {type: 'array'});

        array = to_array(workbook);

        setColView(array[0]);
        /* DO SOMETHING WITH workbook HERE */
    };
    reader.readAsArrayBuffer(f);

}

function setColView(cols) {
//    var html =
//            '<div class="btn btn-primary w3-card colum w3-marginn" ' +
//            'draggable="true" ondragstart="drag(event)"  data-index=""' +
//            'id="col1" > ' +
//            '</div>';
    $("#sheetCols").html('');
    for (var index = 0; index < cols.length; index++) {
        var div = document.createElement("div");
        div.innerHTML =
                '<div class="w3-card btn btn-primary sheetCol w3-margin" ' +
                'draggable="true" ondrop="false" ondragstart="drag(event)"  data-index="' + index + '"' +
                'id="col' + index + '" > ' +
                cols[index] +
                '</div>';
        $("#sheetCols").append(div);
    }
}

var html = $(".sheet-row").html();

function setTableView(arr) {
    //$(".row").remove();

    $(".sheet-table-rows").html('');
    for (var index = 1; index < arr.length; index++) {
        var tr = document.createElement("tr");
        tr.innerHTML = html;
        var tds = $(tr).find("td");

        // convert to time moment('08:00', 'HH:mm:ss')._d
        try {
            arr[index][$("#in_time").attr("data-index")] = moment(arr[index][$("#in_time").attr("data-index")], 'HH:mm:ss')._d.toTimeString().slice(0, 8);
            arr[index][$("#out_time").attr("data-index")] = moment(arr[index][$("#out_time").attr("data-index")], 'HH:mm:ss')._d.toTimeString().slice(0, 8);
            arr[index][$("#date").attr("data-index")] = moment(arr[index][$("#date").attr("data-index")], 'MM-DD-YYYY')._d.format("yyyy-mm-dd");

        } catch (e) {
            error(TIME_FORMAT);
        }

        tds[0].innerHTML = arr[index][$("#emp_id").attr("data-index")];
        tds[1].innerHTML = arr[index][$("#date").attr("data-index")];
        tds[2].innerHTML = arr[index][$("#in_time").attr("data-index")];
        tds[3].innerHTML = arr[index][$("#out_time").attr("data-index")];
        $(".sheet-table-rows").append(tr);

    }
}

var currentSlide = 1;

function next() {
    if (currentSlide > 2) {
        $(".slide-" + currentSlide).slideUp(400);
        currentSlide = 0;
    }

    $(".slide-" + currentSlide).slideUp(400);
    currentSlide++;
    $(".slide-" + (currentSlide)).slideDown(400);
}

function showStepCircle(slide) {
    $(".circle").toggleClass("w3-text-green w3-text-gray");
    $(".step-circle-" + slide).toggleClass("w3-text-gray w3-text-green");
}

function slide1() {
    $(".slide").slideUp(400);
    $(".slide-1").slideDown(400);

    showStepCircle(1);
}

function slide2() {
    if ($("#filename").text().length <= 0) {
        error(SELECT_FILE);
        return;
    }
    $(".slide").slideUp(400);
    $(".slide-2").slideDown(400);

    showStepCircle(2);
}

function slide3() {
    if ($("#emp_id").attr("data-index") == undefined ||
            $("#in_time").attr("data-index") == undefined ||
            $("#out_time").attr("data-index") == undefined ||
            $("#date").attr("data-index") == undefined) {
        error(FILL_FIELD);
        return;
    }
    //getFile($(".excelInput")[0]);
    setTableView(array);
    $(".slide").slideUp(400);
    $(".slide-3").slideDown(400);

    showStepCircle(3);
}

//*********************************
// calculate salary
//*********************************

var dailyRowArr = [];
var emp_dic = {};
var cat_dic = {};
var emp_detail = {};
var lated_days = [];
var emp_report = [];


function convertEmpsToDic() {
    for (var index = 0; index < employees.length; index++) {
        emp_dic[employees[index].id] = employees[index];
    }
}

function convertCatsToDic() {
    for (var index = 0; index < categories.length; index++) {
        cat_dic[categories[index].id] = categories[index];
    }
}

//function getEmpDetail() {
//    for(var index = 0; index < dailyRowArr.length; index ++) {
//        var row = dailyRowArr[index];
//    }
//}

function startCalculate() {
    emp_detail = [];
    lated_days = [];
    emp_report = [];
    dailyRowArr = [];

    loadDailyRows();
}


function loadDailyRows() {
    var dateFrom = $(".dateFrom").val();
    var dateTo = $(".dateTo").val();
    var path = public_path + "/daily/report/data?date_from=" + dateFrom + "&date_to=" + dateTo;

    if ($(".holidays").val() <= 0) {
        error(ERROR_INPUT_VALUE);
        return;
    }

    $.get(path, function (data) {

        dailyRowArr = JSON.parse(data);
        //console.log(dailyRowArr);

        calculateLatedDay();
        calculateAbsentDays();
        calculateLatedDiscount();
        calculateAbsentDiscount();
        calculateNetSalary();
        showEmpDetails();
    });
}

//function defineEmp_detail() {
//    for (var index = 0; index < dailyRowArr.length; index++) {
//        var row = dailyRowArr[index];
//        if (emp_detail[row.emp_id] == undefined) {
//            
//        }
//}
function getHoliday(emp) {
    var fromDate = $(".dateFrom").val();
    var toDate = $(".dateTo").val();

    var data = public_path + "/daily/holiday/get?dateFrom=" + fromDate + "&dateTo=" + toDate + "&emp=" + emp;
    $.get(data, function (d) {
        calculateHoliday(emp, parseInt(d));
    });
}

function calculateLatedDay() {
    for (var index = 0; index < dailyRowArr.length; index++) {
        var row = dailyRowArr[index];

        if (emp_detail[row.emp_id] != undefined) {
            var empCat = emp_dic[row.emp_id].category;

            // main time
            var d1 = moment(cat_dic[empCat].start, 'HH:mm:ss')._d;

            // employee in time
            var d2 = moment(row.in_time, 'HH:mm:ss')._d;

            // calculate lated Day  
            if (d2 > d1) {
                emp_detail[row.emp_id].lated++;

                if (lated_days[row.emp_id] != undefined)
                    lated_days[row.emp_id].push(row);
                else {
                    lated_days[row.emp_id] = [];
                    lated_days[row.emp_id].push(row);
                }
            }

            // set employee days
            emp_detail[row.emp_id].days += 1;

        } else {
            emp_detail[row.emp_id] = {
                empId: row.emp_id,
                empName: emp_dic[row.emp_id].name,
                bankName: emp_dic[row.emp_id].bankName,
                bankNumber: emp_dic[row.emp_id].bankNumber,
                bankBranch: emp_dic[row.emp_id].bankBranch,
                absent: 0,
                original_absent: 0,
                lated: 0,
                allow_lated: 0,
                holiday: 0,
                days: 0,
                discount: 0,
                net_salary: 0,
                day_cost: parseFloat((emp_dic[row.emp_id].salary / 30).toFixed(2)),
                gross_salary: emp_dic[row.emp_id].salary
            };

            // get last holiday
            getHoliday(row.emp_id);

            // set employee days
            emp_detail[row.emp_id].days += 1;

            // add emp to emp report arr
            emp_report.push(row.emp_id);
        }
    }
}

function calculateAbsentDays() {
    var holidays = parseInt($(".holidays").val());
    for (var index = 0; index < emp_report.length; index++) {
        var emp = emp_report[index];
        emp_detail[emp].absent = (30 - holidays) - emp_detail[emp].days;
        emp_detail[emp].original_absent = (30 - holidays) - emp_detail[emp].days;
    }
}

function calculateLatedDiscount() {
    for (var index = 0; index < emp_report.length; index++) {
        var empDetail = emp_detail[emp_report[index]];
        empDetail.discount = 0;
        for (var i = 1; i <= empDetail.lated; i++) {

            if (i >= 1 && i <= MAX_4) {
                empDetail.discount += (empDetail.day_cost / 4);
            } else if (i > MAX_4 && i <= MAX_2) {
                empDetail.discount += (empDetail.day_cost / 2);
            } else if (i > MAX_2) {
                empDetail.discount += empDetail.day_cost;
            }
        }
    }
}

function calculateAbsentDiscount() {
    for (var index = 0; index < emp_report.length; index++) {
        var empDetail = emp_detail[emp_report[index]];

        for (var i = 1; i <= empDetail.absent; i++) {

            if (i >= 1 && i <= MAX_ABSENT_1) {
                empDetail.discount += empDetail.day_cost;
            } else if (i > MAX_ABSENT_1) {
                empDetail.discount += (empDetail.day_cost * 2);
            }
        }
    }
}

function calculateNetSalary() {
    for (var index = 0; index < emp_report.length; index++) {
        var empDetail = emp_detail[emp_report[index]];
        empDetail.net_salary = (parseFloat(empDetail.gross_salary) - parseFloat(empDetail.discount)).toFixed(2);
    }
}

function makeAllowLated(emp) {
    if (emp_detail[emp].allow_lated == 1) {
        emp_detail[emp].allow_lated = 0;
        emp_detail[emp].lated += 1;


        error(DONE);
    } else {
        emp_detail[emp].allow_lated = 1;
        emp_detail[emp].lated -= 1;

        success(DONE);
    }


    // re calculate net salary
    calculateLatedDiscount();
    calculateAbsentDiscount();
    calculateNetSalary();
    showEmpDetails();
}

function updateEmpHoliday(emp, holiday) {
    var fromDate = $(".dateFrom").val();
    var toDate = $(".dateTo").val();

    var data = public_path + "/daily/holiday?dateFrom=" + fromDate + "&dateTo=" + toDate + "&emp=" + emp + "&holiday=" + holiday;
    $.get(data, function (d) {
        //
    });
}

function calculateHoliday(emp, value) {
    var holiday = parseInt(value);
    var patt = /[0-9]/g;
    if (holiday <= 0 || !patt.test(holiday)) {
        error(ERROR_INPUT_VALUE);
        return;
    }

    if (holiday > emp_detail[emp].original_absent) {
        error(ERROR_HOLIDAY_MAX);
    } else {
        emp_detail[emp].absent = emp_detail[emp].original_absent - holiday;
        emp_detail[emp].holiday = holiday;

        success(DONE);
        // update view
        $(".emp_row_" + emp).find(".mHoliday").html(holiday);
        updateEmpHoliday(emp, holiday);

        // re calculate net salary
        calculateLatedDiscount();
        calculateAbsentDiscount();
        calculateNetSalary();
        showEmpDetails();
    }
}

function makeHoliday(emp) {
    var holidayStartDate = new Date(cat_dic[emp_dic[emp].category].holiday_start);
    var holidayEndDate = new Date(cat_dic[emp_dic[emp].category].holiday_end);

    var fromDate = new Date($(".dateFrom").val());
    var toDate = new Date($(".dateTo").val());

    if ((toDate <= holidayEndDate) && (fromDate >= holidayStartDate)) {
        swal(WRITE_HOLIDAY, {
            content: "input",
        }).then(function (value) {
            try {
                calculateHoliday(emp, value);
            } catch (e) {
                error(ERROR_INPUT_VALUE);
            }
        });
    } else {
        error(ERROR_HOLIDAY);
    }
}

var details = [];
function exportReport() {
    if (emp_report.length <= 0) {
        error(MAKE_REPORT_FIRST);
        return;
    }


    var myWindow = window.open("", "", "width=600,height=700");
    details = [];

    for (var index = 0; index < emp_report.length; index++) {
        details.push(emp_detail[emp_report[index]]);
    }

    $.get(public_path + "/daily/report?details=" + JSON.stringify(details), function (data) {
        myWindow.document.write(data);
    });
}
function saveReport() {
    if (emp_report.length <= 0) {
        error(MAKE_REPORT_FIRST);
        return;
    }


    //var myWindow = window.open("", "", "width=600,height=700");
    details = [];

    for (var index = 0; index < emp_report.length; index++) {
        details.push(emp_detail[emp_report[index]]);
    }

    $.get(public_path + "/daily/report?details=" + JSON.stringify(details), function (data) {
        var token = $("input[name=_token]").val();
        $.post(public_path + "/report/store", "_token=" + token + "&data=" + public_path + "/daily/report?details=" + JSON.stringify(details), function (d) {

            var r = JSON.parse(d);

            if (r.status == "1") {
                // show message
                success(r.message);
            }
            else {
                error(r.message);
            }
        });
    });
}

var cloneTr = $(".reportRows .clone")[0];

function showEmpDetails() {
    $(".reportRows").html('');
    for (var index = 0; index < emp_report.length; index++) {
        var emp = emp_report[index];
        var empDetail = emp_detail[emp];
        var tr = document.createElement("tr");
        tr.className = "emp_row_" + emp;
        tr.innerHTML = cloneTr.innerHTML;


        $(tr).find(".emp_id").html(emp);
        $(tr).find(".employee").html(emp_dic[emp].name);
        $(tr).find(".gross").html(empDetail.gross_salary);
        $(tr).find(".absent").html(empDetail.absent);
        $(tr).find(".lated").html(empDetail.lated);
        $(tr).find(".discount").html(empDetail.discount.toFixed(2));
        $(tr).find(".mHoliday").html(empDetail.holiday);
        $(tr).find(".net").html(empDetail.net_salary);

        if (emp_detail[emp].allow_lated == 1)
            $(tr).find(".btn-lated").html(word1 + " <i class='fa fa-check' ></i>");
        else
            $(tr).find(".btn-lated").html(word1);

        if (emp_detail[emp].holiday > 0)
            $(tr).find(".btn-holiday").html(word3 + " <i class='fa fa-check' ></i>");
        else
            $(tr).find(".btn-holiday").html(word2);

        $(tr).find(".btn-lated").attr("data-emp", emp);
        $(tr).find(".btn-holiday").attr("data-emp", emp);

        $(tr).find(".btn-lated").click(function () {
            makeAllowLated(this.getAttribute('data-emp'));
        });
        $(tr).find(".btn-holiday").click(function () {
            makeHoliday(this.getAttribute('data-emp'));
        });

        $(".reportRows").append(tr);
    }
}


$(document).ready(function () {

    // set timepicker plugin
    setTimePicker();

    // convert employees arr to dictionary
    convertEmpsToDic();

    // convert categories arr to dictionary
    convertCatsToDic();
});


// load daily data
loadData();