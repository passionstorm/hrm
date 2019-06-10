<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="upload/avatar/{{Auth::user()->avatar}}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>{{Auth::user()->name}}</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- search form -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
    </div>
  </form>
  <!-- /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li>
      <?php

        $OnlyAdmin='list-item';
        if(Auth::user()->role != Constants::ROLES['admin']){
          $OnlyAdmin = 'none';
        }

        $PreventMember='list-item';
        if(Auth::user()->role == Constants::ROLES['member']){
          $PreventMember = 'none';
        }

      ?>
      <a href="index">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      </a>
    </li>
    <li class="treeview" style="display: {{$PreventMember}}">
      <a href="#">
        <i class="fa fa-users"></i>
        <span>Users</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="users/list"><i class="fa fa-circle-o"></i> List</a></li>
        <li style="display: {{$OnlyAdmin}}"><a href="users/post"><i class="fa fa-circle-o"></i> Add</a></li>
      </ul>
    </li> 
    <li class="treeview" style="display: {{$PreventMember}}">
      <a href="#">
        <i class="fa fa-users"></i>
        <span>Projects</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="projects/list"><i class="fa fa-circle-o"></i> List</a></li>
        <li style="display: {{$OnlyAdmin}}"><a href="projects/post"><i class="fa fa-circle-o"></i> Add</a></li>
      </ul>
    </li>  
    <li class="treeview">
      <a href="#">
        <i class="fa fa-calendar-plus-o"></i>
        <span>OT</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="ots/list"><i class="fa fa-circle-o"></i> List</a></li>
        <li><a href="ots/add"><i class="fa fa-circle-o"></i> Add</a></li>
      </ul>
    </li> 
  </ul>
</section>
<!-- /.sidebar -->
</aside>
