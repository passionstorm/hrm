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
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <?php
                use App\Constants;
                use Illuminate\Support\Facades\Auth;
                $OnlyAdmin = 'list-item';
                if (Auth::user()->role != Constants::ROLE_ADMIN) {
                    $OnlyAdmin = 'none';
                }
                $PreventMember = 'list-item';
                if (Auth::user()->role == Constants::ROLE_MEMBER) {
                    $PreventMember = 'none';
                }
                ?>
                <a href="index">
                    <span>Dashboard</span>
                </a>
            </li>
            <li style="display: {{$PreventMember}}">
                <a href="users/list">
                    <span>Users</span>
                </a>
            </li>
            <li  style="display: {{$PreventMember}}">
                <a href="projects/list">
                    <span>Projects</span>
                </a>
            </li>
            <li>
                <a href="ot/list">
                    <span>OT</span>
                </a>
            </li>
            <li>
                <a href="vacation/list">
                    <span>Vacation</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>