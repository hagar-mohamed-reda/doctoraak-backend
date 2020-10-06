
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{ url('/') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- DataTables -->
<script src="{{ url('/') }}/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/') }}/bower_components/fastclick/lib/fastclick.js"></script>
<script src="{{ url('/') }}/bower_components/select2/dist/js/select2.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/') }}/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('/') }}/dist/js/demo.js"></script>
<!-- nicescroll -->
<script src="{{ url('/') }}/js/jquery.nicescroll.min.js"></script>

<!-- time picker -->
<script src="{{ url('/') }}/plugins/timepicker/bootstrap-timepicker.min.js"></script>


<!-- exports tools -->
<!-- print library -->
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script src="{{ url('/') }}/export/html-docx.js"></script>



<!-- my scripts -->
<script src="{{ url('/') }}/js/iziToast.min.js"></script>
<script src="{{ url('/') }}/js/sweetalert.min.js"></script>
<script src="{{ url('/') }}/js/rate.js"></script>
<script src="{{ url('/') }}/js/vue.js"></script>
<script src="{{ url('/') }}/js/macwindow.js"></script>
<script src="{{ url('/') }}/js/formajax.js"></script>
<script src="{{ url('/') }}/js/app.js"></script>

<!-- datatable plugins -->
@include("template.datatable-plugins") 

<script>
    $(document).ready(function () { 

        $($("table tr th")[0]).click();
    });
</script>

<script>
 

    $(document).ready(function () {
        try {
            formAjax();
        } catch (e) {
        }

        $(".loader").fadeOut(1000);

        //$('#table').DataTable().page.len(5).draw();
        //$('#table').dataTable.ext.errMode = 'throw';
    });

    //Create PDf from HTML...
    function CreatePDFfromHTML(selector, pdfName) {
        var HTML_Width = $(selector).width();
        var HTML_Height = $(selector).height();
        var top_left_margin = 15;
        var PDF_Width = HTML_Width + (top_left_margin * 2);
        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;

        var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

        html2canvas($(selector)[0]).then(function (canvas) {
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            pdf.save(pdfName + ".pdf");
            //$(selector).hide();
        });
    }
    
    function convertToDoc(selector, name)
    { 
        var converted = htmlDocx.asBlob($(selector).html());
        saveAs(converted, name+'.docx');
 
    }
 
    function ExportToExcel(selector, name){ 
       var html = $(selector).html();
       window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    }
</script>
 