<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="upload/avatar/{{Auth::user()->avatar}}" class="img-circle">
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
                <a href="{{ route('home')}}">
                    <span>Dashboard</span>
                </a>
            </li>
            <li style="display: {{$PreventMember}}">
                <a href="{{route('users.list')}}">
                    <span>Users</span>
                </a>
            </li>
            <li style="display: {{$PreventMember}}">
                <a href="{{route('projects.list')}}">
                    <span>Projects</span>
                </a>
            </li>
            <li>
                <a href="{{route('ot.list')}}">
                    <span>OT</span>
                </a>
            </li>
            <li>
                <a href="{{route('vacation.list')}}">
                    <span>Vacation</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>