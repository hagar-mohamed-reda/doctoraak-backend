/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// counter of table row
var counter = 2;
var savedCounter = 1;

function loadData(path) {
    $.get(url + "/employee/data", function (data) {
        $("#tableContainer").html(data);
        //
        dataTable();
        //
        setNicescroll();
    });
}

function showMore() {
    $(".detail").toggle();
}


function editEmployee(employee) {
    $.get(url + "/employee/edit/" + employee, function (data) {
        $(".editModal").html(data);
        $(".editModal").show();
    });
}

function showHolidays(employee) {
    $.get(url + "/employee/holiday/data?emp=" + employee, function (data) {
        $(".holidayModal").html(data);
        $(".holidayModal").show();
        
        dataTable2();
    });
}
/*
 bankNumber 	phone 	bankName 	bankBranch 	holidays 	learning 	image 	cardImage 	cv 	
 criminalPaper 	startDate 	endDate 	salary 	category 	department 	created_at 	updated_at
 */

function onfinished() {
    $(".loader").slideUp(100);
    $(".dataSection").slideDown(500);
    $(".modal-footer").show();
    $(".notmain").remove();

    $('.form-control').val('');
}

function addData(employees) {
    if (employees.length <= 0) {
        onfinished();
        return;
    }
    var employee = employees.shift();
    sendEmployee(employee, function () {
        $("#savedNumber").text(savedCounter);

        savedCounter++;
        addData(employees);
    }, '/employee/store');
}

function updateEmployee(form, id) {
    var employee = {};

    employee.name = form.name.value;
    employee.bankName = form.bankName.value;
    employee.bankNumber = form.bankNumber.value;
    employee.bankBranch = form.bankBranch.value;
    employee.phone = form.phone.value;
    employee.holidays = form.holidays.value;
    employee.learning = form.learning.value;
    employee.startDate = form.startDate.value;
    employee.endDate = form.endDate.value;
    employee.category = form.category.value;
    employee.department = form.department.value;
    employee.type = form.type.value;
    employee.image = form.image.files[0];
    employee.cardImage = form.cardImage.files[0];
    employee.cv = form.cv.files[0];
    employee.criminalPaper = form.criminalPaper.files[0];


    sendEmployee(employee, function () {
        $('.editModal').hide();
        loadImgWithoutCache();
    }, '/employee/update/' + id);

    return false;
}

function addEmployees() {
    var employees = collectData();
    $(".dataSection").slideUp();
    $(".modal-footer").hide();
    $(".loader").slideDown(200);

    //
    $("#tatalNumber").html(employees.length);
    savedCounter = 1;

    addData(employees, counter);
}

function sendEmployee(employee, action, path) {
    var formData = new FormData();

    // employee data
    formData.append("name", employee.name);
    formData.append("bankName", employee.bankName);
    formData.append("bankNumber", employee.bankNumber);
    formData.append("bankBranch", employee.bankBranch);
    formData.append("phone", employee.phone);
    formData.append("holidays", employee.holidays);
    formData.append("learning", employee.learning);
    formData.append("startDate", employee.startDate);
    formData.append("endDate", employee.endDate);
    formData.append("category", employee.category);
    formData.append("department", employee.department);
    formData.append("type", employee.type);

    // files
    formData.append("image", employee.image);
    formData.append("cardImage", employee.cardImage);
    formData.append("cv", employee.cv);
    formData.append("criminalPaper", employee.criminalPaper);

    // _token
    formData.append("_token", $("input[name=_token]").val());


    $.ajax({
        url: public_path + path,
        type: 'POST',
        data: formData,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function (data) {

            var r = JSON.parse(data);

            if (r.status == "1") {
                // show message
                success(r.message);

                // load new data
                loadData();

            }
            else {
                error(r.message);
            }

            if (action != null)
                action(data);
            $(".response")[0].innerHTML += r.message + "<br>";
        }
    });

    return false;
}

function collectData() {
    var employees = [];
    $(".rows tr").each(function () {
        var employee = {};
        employee.name = $(this).find(".name").val();
        employee.bankName = $(this).find(".bankName").val();
        employee.bankNumber = $(this).find(".bankNumber").val();
        employee.bankBranch = $(this).find(".bankBranch").val();
        employee.phone = $(this).find(".phone").val();
        employee.holidays = $(this).find(".holidays").val();
        employee.learning = $(this).find(".learning").val();
        employee.startDate = $(this).find(".startDate").val();
        employee.endDate = $(this).find(".endDate").val();
        employee.category = $(this).find(".category").val();
        employee.department = $(this).find(".department").val();
        employee.type = $(this).find(".type").val();

        employee.image = $(this).find(".image")[0].files[0];
        employee.cardImage = $(this).find(".cardImage")[0].files[0];
        employee.cv = $(this).find(".cv")[0].files[0];
        employee.criminalPaper = $(this).find(".criminalPaper")[0].files[0];

        employees.push(employee);
    });


    return employees;
}

function addRow() {
    // create obj row
    var row = document.createElement("tr");
    row.className = "notmain row" + counter;

    // fill the html obj
    row.innerHTML = $(".row1").html();

    // add row to the table
    $(".rows").append(row);

    // reset 
    $(row).find(".imageView").removeAttr("src");
    $(row).find(".fileView").removeAttr("data-src");
    $(row).find(".fileView").html('');

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

function validate(input) {
    if ($(input).is(":invalid")) {
        $("#addBtn").hide();
        //input.getAttribute("data-name") + " in not valid "
        error(ERROR_INPUT_VALUE);
    } else
        $("#addBtn").show();
}



function validListner() {
    setInterval(function () {
        var valid = true;
        $(".addTable .form-control").each(function () {
            if ($(this).is(":invalid"))
                valid = false;
        });

        valid ? $("#addBtn").show() : $("#addBtn").hide();
    }, 1000);
}

function filter(word) {
    if (word == 0) {
        $('input[aria-controls="table"]').val('');
        $('input[aria-controls="table"]').keyup();

        return;
    }

    $('input[aria-controls="table"]').val(word);
    $('input[aria-controls="table"]').keyup();
}

var X = XLSX;
var XW = {
	/* worker message */
	msg: 'xlsx',
	/* worker scripts */
	worker: './xlsxworker.js'
};

function loadExcel(e) {
    var files = e.target.files, f = files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, {type: 'array'});

        var obj = to_array(workbook);
         
        viewData(obj);
        
        /* DO SOMETHING WITH workbook HERE */
    };
    reader.readAsArrayBuffer(f);
}

function viewData(obj) {
    $(".importTable").html('');
    for(var i = 0; i < obj.length; i ++) {
        var row = obj[i];
        var tr = document.createElement("tr");
        
        for(var j = 0; j < row.length; j ++) {
            var col = row[j];
            var td = document.createElement("td");
            td.innerHTML = col;
            
            tr.appendChild(td);
        }
        
        $(".importTable").append(tr);
    }
}

var to_array = function to_array(workbook) {
    var result = {};
    workbook.SheetNames.forEach(function (sheetName) {
        var roa = X.utils.sheet_to_json(workbook.Sheets[sheetName], {header: 1});
        if (roa.length)
            result = roa;
    });
    
    //console.log(result);
    return  result;//JSON.stringify(result, 2, 2);
};

$(document).ready(function () {
    loadData();

    //  
    validListner();
});