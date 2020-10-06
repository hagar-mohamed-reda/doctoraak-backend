

// set height of the frame
$(".frame").css("height", (window.innerHeight - 50) + "px");

function setNicescroll() {
//        //background: rgba(255,255,255,0.33333),
    $(".nicescroll").niceScroll(
            {
                cursoropacitymin: 0.1,
                cursorcolor: "rgb(255,255,255)",
                cursorborder: '7px solid gray',
                cursorborderradius: 16,
                autohidemode: 'leave'
            });
    $(document).mousemove(function () {
        $(".nicescroll").getNiceScroll().resize();
    });
}

setNicescroll();


// play sound
function playSound(name) {
    try {
        var sound = new Audio(public_path + "/audio/" + name + ".mp3");
        sound.play();
    } catch (e) {
        playSound(name);
    }
}

function dataTable() {
    $('#table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "desc"]]
    });
}

function dataTable2() {
    $('.dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "desc"]]
    });
}

function dataTableWithoutPaging() {
    $('#table-no-paging').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "desc"]]
    });
}

function sendUrl(url, feedback, btn) {
    var html = '';
    if (btn != undefined) {
        html = $(btn).html();
        $(btn).html('<i  class="fa fa-spin fa-spinner" ></i>');
    }
    $.get(url, function (response) {
        if (response.status == 1) {
            success(response.message);
            $('#table').DataTable().ajax.reload();
        }
        else
            error(response.message);

        if (feedback != undefined)
            feedback(response);

        if (btn != undefined) { 
            $(btn).html(html);
        }
    })
}

function success(message, title, img) {
    if (img == undefined || img.length <= 0)
        img = public_path + "/image/reportLogo.png";

    if (title == undefined || title.length <= 0)
        title = "doctoraak";

    playSound("not2");
    var html =
            "<table class='' style='direction: ltr!important' >" +
            "<tr>" +
            "<td><img src='" + img + "' class='w3-circle' width='60px' height='60px' ></td>" +
            "<td style='padding:7px' ><b class='w3-large' >" + title + "</b><br>" +
            "<p style='max-width: 200px;margin-top: 5px!important' >" + message + "</p>" +
            "</td>" +
            "</tr>" +
            "</table>";
    $instance = iziToast.show({
        class: 'shadow izitoast',
        message: html,
    });

    $(".izitoast").mousedown();
}

function error(message, title, img) {
    if (img == undefined || img.length <= 0)
        img = public_path + "/image/reportLogo.png";

    if (title == undefined || title.length <= 0)
        title = "doctoraak";

    playSound("not2");
    var html =
            "<table class='' style='direction: ltr!important' >" +
            "<tr>" +
            "<td><img src='" + img + "' class='w3-circle' width='60px' height='60px' ></td>" +
            "<td style='padding:7px' ><b class='w3-large' >" + title + "</b><br>" +
            "<p style='max-width: 200px;margin-top: 5px!important' >" + message + "</p>" +
            "</td>" +
            "</tr>" +
            "</table>";
    iziToast.show({
        class: 'w3-pale-red shadow izitoast',
        message: html,
    });

    $(".izitoast").click();
}


function remove(text, url, div, action) {
    swal({
        title: "😧 هل انت متاكد?" + text,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            if (div != undefined) {
                $(div).remove();
            }
            $.get(url, function (data) {
                if (data.status == 1) {
                    success(data.message);
                    $($("table tr th")[0]).click();
                } else {
                    error(data.message);
                }
            });
        } else {
        }
    });
}

function removeOldMapScripts() {
    var times = [];
    $('script').each(function(){
        if (this.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ow5PXyqH-gJwe2rzihxG71prgt4NRFQ&callback=initMap") {
            //times.push(this);
            $(this).remove();
        }
    });
    
    if (times.length >= 2) {
        for(var index = 1; index < times.length; index ++) {
            $(times[index]).remove();
        }
    }
}

function showPage(url) {
    var html = "<br><br><br><br><br><center><i class='fa fa-spin fa-refresh w3-jumbo' ></i></center>";
    $(".frame").html(html);
    removeOldMapScripts();
    $.get(url, function (response) {
        $(".frame").html(response);
    });
}

function showPage2(url) {
    window.open(public_path + "/" + url, "report", "width=700,height=600");
}
 
function edit(route, modal, place) {
    var r = '<br><br><br><br><div class="text-center w3-xlarge w3-text-indigo shadow w3-white w3-round w3-padding w3-center" style="max-width: 200px!important;margin: auto"  ><i class="fa fa-spin fa-spinner w3-margin" ></i> <br> loading !!</div>';
        if (modal) { 
            $("." + place).html(r);
            //
            $('#' + modal).modal('show');
        } else {
            $(".editModalPlace").html(r);
            //
            $('#editModal').modal('show');
        }
        
    $.get(route, function (r) {
        if (modal) { 
            $("." + place).html(r);
            //
            $('#' + modal).modal('show');
        } else {
            $(".editModalPlace").html(r);
            //
            $('#editModal').modal('show');
        }
        
        //
        formAjax(true);
    });
}


function viewImage(image) { 
    var modal = document.createElement("div");
    modal.className = "w3-modal w3-block nicescroll";
    modal.style.zIndex = "10000000";

    modal.innerHTML = "<center  ><div style='width: 60%' class='w3-animate-zoom' > " +
            "<img src='" + image.src + "' style='max-width: 60%' />"
            + "</div></center>  ";

    modal.onclick = function () {
        modal.remove();
    };

    document.body.appendChild(modal);
}

function viewFile(div) {

    var modal = document.createElement("div");
    modal.className = "w3-modal w3-block nicescroll";
    modal.style.zIndex = "10000000";
    modal.style.paddingTop = "20px";

    modal.innerHTML = "<center><div class='w3-animate-zoom' > " +
            '<iframe frameborder="0" scrolling="no" width="400" height="600" src="' + div.getAttribute("data-src") + '" ></iframe>'
            + "</div></center>  ";

    modal.onclick = function () {
        modal.remove();
    };

    document.body.appendChild(modal);
}

function loadImage(input, event) {
    var file = event.target.files[0];

    if (file.size > (MAX_UPLOADED_IMAGE * 1000 * 1000)) {
        error(ERROR_UPLOAD_IMAGE_MESSAGE);
        return;
    }
    $(input).parent().find(".imageView")[0].src = URL.createObjectURL(file);
}

function loadFile(input, event) {
    var span = $(input).parent().find(".fileView")[0];
    var file = event.target.files[0];

    if (file.size > (MAX_UPLOADED_FILE * 1000 * 1000)) {
        error(ERROR_UPLOAD_FILE_MESSAGE);
        return;
    }


    span.innerHTML = file.name;
    $(span).attr('data-src', URL.createObjectURL(file));
}

function loadImgWithoutCache() {
    $('img').each(function () {
        if (this.src.length > 0)
            $(this).attr('src', $(this).attr('src') + '?' + (new Date()).getTime());
    });
}

function setIcon() {
    $(".icon").each(function () {
        var width = $(this).attr("width");
        var name = $(this).attr("name");
        var html = "<img src='" + public_path + "/image/" + name + "' width='" + width + "' style='margin:3px' >";
        $(this).html(html);
    });
}

$(document).ready(function () {
    loadImgWithoutCache();
});


function openWindow(src, title) {
    var w = new MacWindow(src, title);
    w.maximize();
    w.open();
}


function toggleCol(table, cols) {
    for (var i = 0; i < cols.length; i++) {
        var index = cols[i];
        // Get the column API object
        var column = table.column(index);

        // Toggle the visibility
        column.visible(!column.visible());
    }
    table.columns.adjust().draw();
}