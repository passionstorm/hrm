@extends('layout.index')
@section('css')
<style>
    #spc:after {
        content: none;
    }

    .user_area li {
        width: 50%;
        text-align: center;

    }

    .user_area li a {
        background: #999999;
        font-size: 16px;
        padding: 10px 0;
        text-decoration: none;
        color: #fff;
    }

    .td_avatar {
        width: 84px;
    }

    .td_avatar img {
        width: 84px;
        height: auto;
    }

    ul.headers li:nth-child(1) {
        width: 40px;
    }

    ul.headers li:nth-child(2) {
        width: 158px;
    }

    ul.headers li:nth-child(3) {
        width: 180px;
    }

    ul.headers li:nth-child(4) {
        width: 81px;
    }

    input[type="checkbox"] + label > span.checkbox, input[type="checkbox"]:checked + label > span.checkbox {
        background-position: right center;
    }

    li div.pic {
        left: 35px;
    }

    .menu-bar {
        margin-top: 3px;
    }

    .menu-bar .menu-bar-item {
        padding-right: 0;
        padding-left: 0;
    }

    .menu-bar .action {
        text-align: center;
        padding: 4px 8px;
    }

    .menu-bar .action .btn {
        float: right;
    }

    table.statistic {
        text-align: center;
        margin-bottom: 0;
    }

    table.statistic th:nth-child(1) {
        width: 100px;
        background: #eeeeee;
        color: #444444;
    }

    table.statistic th {
        width: 100px;
        color: #EDF6F9;
        background: #999999;
    }
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('messages.success')

    <?php

    use App\Constants;
    use Illuminate\Support\Facades\Auth;

    $OnlyAdmin = '';
    if (Auth::user()->role != Constants::ROLE_ADMIN) {
        $OnlyAdmin = 'd-n';
    }
    ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="display: inline-block">
            User Management
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-8">
                        <table class="table table-bordered statistic">
                            <thead>
                            <tr>
                                <th>Role</th>
                                <th>Active</th>
                                <th>Leave</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Admin</td>
                                <td>{{$statis[Constants::ROLE_ADMIN]}}</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Staff</td>
                                <td>{{$statis[Constants::ROLE_STAFF]}}</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Member</td>
                                <td>{{$statis[Constants::ROLE_MEMBER]}}</td>
                                <td>0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-4">
                        <div class="clearfix"></div>
                        <div>
                            <a href="users/edit" class="btn btn-primary" style="float: right">NEW USER</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="box">
                <div class="box-body">

                    <div class="row" style="margin-bottom: 20px">
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="osau">
                                    <div class="icheckbox_flat-green"><input type="checkbox" id="osau" checked
                                                                             class="hiddent">Show only
                                        available users
                                    </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs user_area">
                        <li class="active"><a data-toggle="tab" href="#staff_area">Manager</a></li>
                        <li><a data-toggle="tab" href="#member_area">Member</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="staff_area" class="tab-pane fade in active">
                            <div class="row menu-bar">
                                <div class="col-xs-6 menu-bar-item">
                                    <div class="type_search">
                                        <input type="text" id="setting_user_search_content"
                                               placeholder="ここで名前を入力することで検索できます" maxlength="20">
                                        <input type="hidden" id="setting_user_search_for" value="members">
                                        <button id="btn_search"></button>
                                    </div>
                                </div>
                                <div class="col-xs-6 action menu-bar-item">
                                    <button class="btn btn-danger disabled">削除</button>
                                </div>
                            </div>
                            <ul class="headers">
                                <li>
                                    <input type="checkbox" id="chkItem_all_Member">
                                    <label for="chkItem_all_Member">
                                        <span class="checkbox">&nbsp;</span>
                                    </label>
                                </li>
                                <li>Name</li>
                                <li>Office</li>
                                <li>Experience</li>
                            </ul>
                            <ul class="table-body">
                                @foreach($users as $u)
                                @if($u->role != Constants::ROLE_MEMBER)
                                <li class="@if($u->is_deleted){{" is_deleted is_deleted_bg
                                "}}@endif">

                                <div>
                                    <input type="checkbox" id="chkItem_{{$u->id}}" value="{{$u->id}}">
                                    <label for="chkItem_{{$u->id}}">
                                        <span class="checkbox">&nbsp;</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="users/edit/{{$u->id}}">
                                        <div class="pic">
                                            <img src="dist/img/avatar.png"/>
                                        </div>
                                        <div class="member_name">
                                            <span class="name">{{$u->name}}</span>
                                        </div>
                                    </a>
                                </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        <div id="member_area" class="tab-pane fade">
                            <div class="row menu-bar">
                                <div class="col-xs-6 menu-bar-item">
                                    <div class="type_search">
                                        <input type="text" id="setting_user_search_content"
                                               placeholder="ここで名前を入力することで検索できます" maxlength="20">
                                        <input type="hidden" id="setting_user_search_for" value="members">
                                        <button id="btn_search"></button>
                                    </div>
                                </div>
                                <div class="col-xs-6 action menu-bar-item">
                                    <button class="btn btn-danger disabled">削除</button>
                                </div>
                            </div>
                            <ul class="headers">
                                <li>
                                    <input type="checkbox" id="chkItem_all_Member">
                                    <label for="chkItem_all_Member">
                                        <span class="checkbox">&nbsp;</span>
                                    </label>
                                </li>
                                <li>Name</li>
                                <li>Office</li>
                                <li>Experience</li>
                            </ul>
                            <ul class="table-body">
                                @foreach($users as $u)
                                @if($u->role == Constants::ROLE_MEMBER)
                                <li class="@if($u->is_deleted){{" is_deleted is_deleted_bg
                                "}}@endif">

                                <div>
                                    <input type="checkbox" id="chkItem_{{$u->id}}" value="{{$u->id}}">
                                    <label for="chkItem_{{$u->id}}">
                                        <span class="checkbox">&nbsp;</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="users/edit/{{$u->id}}">
                                        <div class="pic">
                                            <img src="dist/img/avatar.png"/>
                                        </div>
                                        <div class="member_name">
                                            <span class="name">{{$u->name}}</span>
                                        </div>
                                    </a>
                                </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- page script -->
<script>
    $(document).ready(function () {
        $('#osau').change(function () {
            if (!$(this).is(':checked')) {
                $('.is_deleted').css('display', 'table-row');
            } else {
                $('.is_deleted').css('display', 'none');
            }
        });
    });
</script>

@endsection