<div style="width: 220px" >
    <a href="#" onclick="openWindow('{{ url('/') }}/insuranceuserdashboard/lab/show/{{ $id }}', 'عرض طلب معمل التحاليل')" class="btn btn-success btn-flat btn-sm" >
        <i class="fa fa-desktop" ></i> <span> عرض </span>
    </a>
    <a href="#" onclick="acceptOrder('{{ url('/') }}/insuranceuserdashboard/lab/accept/{{ $id }}', this)" class="btn btn-primary btn-flat btn-sm" >
        <i class="fa fa-check-square-o" ></i> <span> موافقه </span>
    </a>
    <a href="#" onclick="acceptOrder('{{ url('/') }}/insuranceuserdashboard/lab/refuse/{{ $id }}', this)" class="btn btn-danger btn-flat btn-sm" >
        <i class="fa fa-close" ></i> <span> رفض </span>
    </a>
</div>
