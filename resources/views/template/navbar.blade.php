
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar shadow w3-white" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('/image/doctor.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ (App\User::auth())? App\User::auth()->name : '' }}</p>
                <a href="#">
                    <i class="fa fa-circle text-success"></i>
                </a>
            </div>
            <br>
            <br>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu font" data-widget="tree">
            <li class="header">قوائم التجول الاساسيه</li>

            <li class="treeview font" onclick="showPage('dashboard')" >
                <a href="#">
                    <i class="fa fa-dashboard" ></i> <span>لوحة التحكم</span>
                </a>
            </li>
            <!--
            <li class="treeview font" onclick="showPage('dashboard/item')" >
                <a href="#">
                    <i class="fa fa-cubes"></i> <span>menu item 1</span>
                </a>
            </li>
              -->
            @if (App\Role::canAccess(null, App\Role::$PATIENT))
            <li class="treeview font" onclick="showPage('patient')" >
                <a href="#">
                    <i class="fa fa-wheelchair" ></i> <span>{{ __('patient') }}</span>
                </a>
            </li>
            @endif
            
            @if (App\Role::canAccess(null, App\Role::$INCUBATION)) 
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-child" ></i> 
                    <span>{{ __('incubation') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('incubation')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('incubation/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('incubation locations') }}
                        </a>
                    </li> 
                </ul>
            </li>
            @endif
            
            @if (App\Role::canAccess(null, App\Role::$ICU))
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-heartbeat" ></i> 
                    <span>{{ __('icu') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('icu')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('icu/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('icu locations') }}
                        </a>
                    </li> 
                </ul>
            </li>
            @endif
            
            @if (App\Role::canAccess(null, App\Role::$DOCTOR))
            
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-user-md" ></i> 
                    <span>{{ __('doctors & clinics') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('doctor')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show doctors') }}
                        </a>
                    </li>
                    <li onclick="showPage('clinic')" >
                        <a href="#">
                            <i class="fa fa-bank" ></i>
                            {{ __('clinic') }}
                        </a>
                    </li> 
                    <li onclick="showPage('clinic/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('clinic maps') }}
                        </a>
                    </li> 
                    <li onclick="showPage('clinicorder')" >
                        <a href="#">
                            <i class="fa fa-address-book" ></i>
                            {{ __('reservations') }}
                        </a>
                    </li> 
                </ul>
            </li>
            @endif
           
            
            @if (App\Role::canAccess(null, App\Role::$DOCTOR)) 
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-medkit" ></i> 
                    <span>{{ __('pharmacy') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('pharmacy')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('pharmacy?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add pharmacy') }}
                        </a>
                    </li>
                    <li onclick="showPage('pharmacy/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('pharmacy maps') }}
                        </a>
                    </li> 
                    <li onclick="showPage('pharmacydoctor')" >
                        <a href="#">
                            <i class="fa fa-user-md" ></i>
                            {{ __('pharmacy doctors') }}
                        </a>
                    </li> 
                    <li onclick="showPage('pharmacyorder')" >
                        <a href="#">
                            <i class="fa fa-address-book" ></i>
                            {{ __('pharmacy orders') }}
                        </a>
                    </li> 
                </ul>
            </li>
            @endif
 

            @if (App\Role::canAccess(null, App\Role::$LAB)) 
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-thermometer" ></i> 
                    <span>{{ __('lab') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('lab')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('lab?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add lab') }}
                        </a>
                    </li>
                    <li onclick="showPage('lab/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('lab maps') }}
                        </a>
                    </li> 
                    <li onclick="showPage('labdoctor')" >
                        <a href="#">
                            <i class="fa fa-user-md" ></i>
                            {{ __('lab doctors') }}
                        </a>
                    </li> 
                    <li onclick="showPage('laborder')" >
                        <a href="#">
                            <i class="fa fa-address-book" ></i>
                            {{ __('lab orders') }}
                        </a>
                    </li> 
                </ul>
            </li> 
            @endif
            
            @if (App\Role::canAccess(null, App\Role::$RADIOLOGY)) 
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-thermometer" ></i> 
                    <span>{{ __('radiology') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('radiology')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('radiology?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add radiology') }}
                        </a>
                    </li>
                    <li onclick="showPage('radiology/map')" >
                        <a href="#">
                            <i class="fa fa-map-marker" ></i>
                            {{ __('radiology maps') }}
                        </a>
                    </li> 
                    <li onclick="showPage('radiologydoctor')" >
                        <a href="#">
                            <i class="fa fa-user-md" ></i>
                            {{ __('radiology doctors') }}
                        </a>
                    </li> 
                    <li onclick="showPage('radiologyorder')" >
                        <a href="#">
                            <i class="fa fa-address-book" ></i>
                            {{ __('radiology orders') }}
                        </a>
                    </li> 
                </ul>
            </li> 
            @endif
             
            @if (App\Role::canAccess(null, App\Role::$INSURANCE)) 
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-medkit" ></i> 
                    <span>{{ __('insurances') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('insurance')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('insurance?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add insurance') }}
                        </a>
                    </li>
                    <li onclick="showPage('userinsurance?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add insurance user') }}
                        </a>
                    </li>
                    <li onclick="showPage('userinsurance')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('users') }}
                        </a>
                    </li>
                     
                </ul>
            </li> 
            @endif
  
            @if (App\Role::canAccess(null, App\Role::$USER))
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-users" ></i> 
                    <span>{{ __('users') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('user')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('show') }}
                        </a>
                    </li>
                    <li onclick="showPage('user?action=create')" >
                        <a href="#">
                            <i class="fa fa-circle" ></i>
                            {{ __('add user') }}
                        </a>
                    </li>  
                </ul>
            </li> 
            @endif
            
            @if (App\Role::canAccess(null, App\Role::$OPTION))
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-cogs" ></i> 
                    <span>{{ __('options') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage('area')" ><a href="#"><i class="fa fa-circle-o" ></i> {{ __('areas') }}</a></li>
                    <li onclick="showPage('city')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('cities') }}</a></li>
                    <li onclick="showPage('specialization')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('specializations') }}</a></li>
                    <li onclick="showPage('degree')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('degrees') }}</a></li>
                    <li onclick="showPage('medicine')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('medicines') }}</a></li>
                    <li onclick="showPage('medicinetype')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('medicinetypes') }}</a></li>
                    <li onclick="showPage('ray')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('rays') }}</a></li>
                    <li onclick="showPage('analysis')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('analysis') }}</a></li>
                    <li onclick="showPage('setting')" ><a href="#"><i class="fa fa-circle-o" ></i>{{ __('setting') }}</a></li>
                    <li onclick="showPage('setting/translation')" ><a href="#"><i class="fa fa-language" ></i>{{ __('translation') }}</a></li>
                </ul>
            </li>
            @endif

            @if (App\Role::canAccess(null, App\Role::$REPORT))
            <li class="treeview font"  >
                <a href="#">
                    <i class="fa fa-line-chart" ></i> 
                    <span>{{ __('reports') }}</span>
                    <span class="pull-right-container" style="left: 0!important" >
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li onclick="showPage2('report/places')" ><a href="#"><i class="icon" name="map-location.png" width="15px" ></i>تقرير اماكن الفئاء</a></li>
                    <li onclick="showPage2('report/clinicorder')" ><a href="#"><i class="icon" name="clinicorder.png" width="15px" ></i>تقرير حجزات العيادات</a></li>
                    <li onclick="showPage2('report/pharmacyorder')" ><a href="#"><i class="icon" name="pharmacyorder.png" width="15px" ></i>تقرير طلبات الصيدليات</a></li>
                    <li onclick="showPage2('report/laborder')" ><a href="#"><i class="icon" name="laborder.png" width="15px" ></i>تقرير طلبات معامل التحاليل</a></li>
                    <li onclick="showPage2('report/radiologyorder')" ><a href="#"><i class="icon" name="radiologyorder.png" width="15px" ></i>تقرير طلبات معامل الاشعه</a></li>
                    <li onclick="showPage2('report/modelorder')" ><a href="#"><i class="icon" name="group.png" width="15px" ></i>تقرير طلبات جميع المستخدمين</a></li>
                    <li onclick="showPage2('report/userview')" ><a href="#"><i class="icon" name="group.png" width="15px" ></i>تقرير مشاهدات مستخدمين التطبيق</a></li>
                    <li onclick="showPage2('report/insurance')" ><a href="#"><i class="icon" name="insurance.png" width="15px" ></i>تقرير شركات التامين </a></li>
                    <li onclick="showPage2('report/payment')" ><a href="#"><i class="icon" name="payment.png" width="15px" ></i>تقرير الدفع </a></li>
                    <li onclick="showPage2('report/doctor')" ><a href="#"><i class="icon" name="doctor.png" width="15px" ></i>تقرير الدكاتره </a></li> 
                </ul>
            </li>
            @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
