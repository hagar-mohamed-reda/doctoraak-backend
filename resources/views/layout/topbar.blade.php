
  <header class="main-header shadow">
    <!-- Logo -->
    <a href="#" class="logo" style="background-image: url('{{ url("/image/header.jpg") }}');background-size: cover" >

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">

          </li>
          <!-- Notifications: style can be found in dropdown.less -->

          @yield("menus")
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ url('/') }}/image/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ $name }}</span>
            </a>
              <ul class="dropdown-menu w3-white w3-card" >
              <!-- User image -->
              <li class="user-header" style="background-image: url('{{ url("/image/back.jpg") }}');background-size:cover">
                <img src="{{ url('/') }}/image/user.png" class="img-circle" alt="User Image">

                  <p>
                      <span class="label label-primary" >{{ $name }}</span>
                    </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer w3-white">
                <center  >
                        <a href="{{ $route }}" class="btn btn-default btn-flat">تسجيل الخروج</a>
                </center>
                <!--
                <div class="pull-right">
                  <a href="#" onclick="showPage('#')" class="btn btn-default btn-flat">الملف الشخصى</a>
                </div>
            -->
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
