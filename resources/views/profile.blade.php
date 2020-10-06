 
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="margin: 0px" >
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 class='font' >
                        الملف الشخصى
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/') }}/dashboard"><i class="fa fa-dashboard"></i> لوحة التحكم</a></li> 
                        <li class="active">الملف الشخصى</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="row">
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img class="profile-user-img img-responsive img-circle " 
                                         id="profileImage" src="{{ url('/') }}/image/user.png"  
                                         alt="User profile picture">

                                    <h3 class="profile-username text-center">{{ $user->name }}</h3>


                                </div>
                                <!-- /.box-body -->
                            </div> 
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#activity" data-toggle="tab">معلومات الملف الشخصى</a></li> 
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="activity">
                                        <form action="{{ url('/') }}/profile/update" role="form" class="form" id="addUser" method="post" autocomplete="off" >
                                            {{ csrf_field() }}
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="">الاسم *</label>
                                                    <input type="text" class="form-control" name="name" placeholder="name" required="" value="{{ $user->name }}" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="">الاميل *</label>
                                                    <input type="text" class="form-control" readonly="" name="email" placeholder="email" required="" value="{{ $user->email }}" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="">كلمة المرور *</label>
                                                    <input type="password" class="form-control" name="password" placeholder="password" required="" value="{{ $user->password }}" >
                                                </div>  
                                                <div class="form-group">
                                                    <input type="file" class="hidden file" name="image" onchange="loadImage(this, event)" >
                                                </div>  
                                            </div>
                                            <!-- /.box-body -->

                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-primary btn-flat">تعديل</button> 
                                            </div>
                                        </form>
                                    </div> 
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </section>
                <!-- /.content -->
                <script>
                formAjax();
                </script>
