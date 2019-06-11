@extends('layout.index')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<style>
    #spc:after {
        content: none;
    }

    .d-n {
        display: none !important;
    }

    .iga-1 {
        border-right: 1px solid #d2d6de !important;
        padding-left: 6px !important;
        padding-right: 6px !important;
    }

    .input-group-addon {
        width: 0% !important;
    }

    .is_deleted {
        display: none
    }

    .is_deleted_bg {
        background: #d3d3d3;
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
        <h1>
            List of users
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="osau">
                                        <div class="icheckbox_flat-green"><input type="checkbox" id="osau"
                                                                                 checked class="hiddent">Show only
                                            available users
                                        </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <a href="users/edit" class="btn btn-primary">New user</a>
                            </div>
                        </div>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                @if (Auth::user()->role == Constants::ROLE_ADMIN)
                                <th>Salary</th>
                                @endif
                                <th id='spc' class='{{$OnlyAdmin}}'></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $u)
                            <tr class="@if($u->is_deleted){{" is_deleted is_deleted_bg
                            "}}@endif">
                            <td>{{$u->id}}</td>
                            <td>{{$u->name}}</td>
                            @if (Auth::user()->role == Constants::ROLE_ADMIN)
                            <td>{{$u->salary}}</td>
                            @endif
                            <td style="text-align: center" class='{{$OnlyAdmin}}'><a
                                        href="users/edit/{{$u->id}}">Edit</a></td>
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
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
    $(function () {
        $('#example1').DataTable();
        $('#example2').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    });

    //chuyen doi hien thi va ko hien thi is_deleted user
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