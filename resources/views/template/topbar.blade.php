
  <header class="main-header shadow w3-dark-doctoraak">
    <!-- Logo -->
    <a href="{{ url('/') }}/home" class="logo" style="background-image: url('{{ url("/image/header.jpg") }}');background-size: cover" >
       
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top w3-dark-doctoraak">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-globe"></i> 
              <span class="label label-success"></span>
            </a>
              <ul class="dropdown-menu" style="width:140px" > 
              <li>
                <!-- inner menu: contains the actual data -->
<!--                <ul class="menu">
                  <li> start message 
                    <a href="{{ url('/') }}/lang/ar">
                      <div class="pull-left">
                        <img src="{{ url('/') }}/image/ar.png" class="img-circle" alt="User Image">
                      </div>
                        <div class="h4 font" >
                        عربى
                      </div> 
                    </a>
                  </li>
                  <li> start message 
                    <a href="{{ url('/') }}/lang/en">
                      <div class="pull-left">
                        <img src="{{ url('/') }}/image/en.png" class="img-circle" alt="User Image">
                      </div>
                        <div class="h4" >
                        English
                      </div> 
                    </a>
                  </li>
                   end message 
                </ul>-->
              </li> 
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ url('/') }}/image/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ $user->name }}</span>
            </a>
              <ul class="dropdown-menu w3-white w3-card" >
              <!-- User image -->
              <li class="user-header" >
                <img src="{{ url('/') }}/image/user.png" class="img-circle" alt="User Image">

                  <p>
                      <span class="label label-primary" >{{ $user->name }}</span>
                    </p>
              </li> 
              <!-- Menu Footer-->
              <li class="user-footer w3-white">
                <div class="pull-left">
                    <a href="#" onclick="showPage('profile')" class="btn btn-default btn-flat">الملف الشخصى</a>
                </div>
                <div class="pull-right">
                  <a href="{{ url('/') }}/logout" class="btn btn-default btn-flat">تسجيل الخروج</a>
                </div>
              </li>
            </ul>
          </li> 
        </ul>
      </div>
    </nav>
  </header>