<!-- Left side column. contains the logo and sidebar -->
@php
  use App\Department;
  $department = Department::where('id',Auth::user()->department_id)->first()->name;
@endphp
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          @php
            $avatar = Auth::user()->avatar;
          @endphp
          <img src="https://s-media-cache-ak0.pinimg.com/originals/2f/1c/ee/2f1cee39bfc1d4588ce3c55ae1e90030.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <p>{{ ucfirst($department) }}</p>
        </div>
      </div>
  
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header"><hr></li>
        @yield('menu')
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>