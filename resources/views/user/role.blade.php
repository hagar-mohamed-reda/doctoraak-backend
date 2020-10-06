@extends("layout.app", ['title' => 'تعديل صلاحيات المستخدمين'])
 

@section("styles")
<style>
    input[type=checkbox] {
        display: block!important;
        visibility: visible!important;
        width: auto!important;
    }
</style>
@endsection

@section("content")

<div class="box-header witd-border">
    <h3 class="box-title">تعديل صلاحيات المستخدم {{ $user->name }}</h3>
</div>
<br>
<!-- /.box-header -->
<!-- form start --> 
<div>
    <table class="table table-bordered text-right" style="direction: rtl" >
        <tr>
            <td>الصلاحيه</td>
            <td>التفعيل</tr>
        </tr>
        <tr class="w3-light-blue" >
            <td>حدد الكل</td>
            <td>
                <div class="">
                    
                        <input type="checkbox"   onclick="$('.role').click()"  >
                        
                    
                </div>
            </td>
        </tr>
        
        <tr>
            <td>المرضى</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$PATIENT }})" {{ App\Role::canAccess($user->id, App\Role::$PATIENT)? 'checked' : '' }} >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>الحضانات</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$INCUBATION }})" {{ App\Role::canAccess($user->id, App\Role::$INCUBATION)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>العنايه المركزه</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$ICU }})" {{ App\Role::canAccess($user->id, App\Role::$ICU)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>الدكاتره و العيادات</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$DOCTOR }})" {{ App\Role::canAccess($user->id, App\Role::$DOCTOR)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>الصيدليات</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$PHARMACY }})" {{ App\Role::canAccess($user->id, App\Role::$PHARMACY)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>معامل التحاليل</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$LAB }})" {{ App\Role::canAccess($user->id, App\Role::$LAB)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>معامل الاشعه</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$RADIOLOGY }})" {{ App\Role::canAccess($user->id, App\Role::$RADIOLOGY)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>شركات التامين</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$INSURANCE }})" {{ App\Role::canAccess($user->id, App\Role::$INSURANCE)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>المستخدمين</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$USER }})" {{ App\Role::canAccess($user->id, App\Role::$USER)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>التقارير</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$REPORT }})" {{ App\Role::canAccess($user->id, App\Role::$REPORT)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
        <tr>
            <td>الاعدادات</td>
            <td>
                <div class="">
                    
                        <input type="checkbox" class="role"  onchange="addRole({{ App\Role::$OPTION }})" {{ App\Role::canAccess($user->id, App\Role::$OPTION)? 'checked' : '' }}  >
                        
                    
                </div>
            </td>
        </tr>
    </table>
    
    <br>
    <br>
        <button type="button" class="btn btn-default btn-flat" onclick="showPage('user')" >رجوع</button>
</div>
@endsection

@section("scripts") 
<script>
    function addRole(role) {
        var user = {{ $user->id }};
        
        $.get("{{ url('/') }}/role/update?role="+role+"&user_id="+user, function(data){
            success(data.message);
        });
    }

    formAjax();
</script>
@endsection
