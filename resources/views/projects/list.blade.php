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
        background-color: #d3d3d3 !important
    }
    .pd-r-15{padding-right: 15px}
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('messages.success')

    <?php

    use Illuminate\Support\Facades\Auth;
    use App\Constants;

    $OnlyAdmin = '';
    if (Auth::user()->role != Constants::ROLE_ADMIN) {
        $OnlyAdmin = 'd-n';
    }
    ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            List of projects
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
                                </div>
                                <label for="osau">
                                    <div class="icheckbox_flat-green"><input type="checkbox" id="osau"
                                                                             checked class="hiddent">Show only
                                        available projects
                                    </div>
                                </label>
                            </div>
                            <div class="col pull-right pd-r-15">
                                <a href="projects/edit" class="btn btn-primary">Add project</a>
                            </div>
                        </div>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Budget</th>
                                <th>Deadline</th>
                                <th>Participants</th>
                                <th id='spc' class='{{$OnlyAdmin}}'></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $p)
                            <?php
                            $participants_c = 0;
                            if( $p->participants ){
                            $participants_c = count( explode(',',$p->participants) );
                            }
                            ?>
                            <tr class="@if($p->is_deleted){{" is_deleted is_deleted_bg "}}@endif">
                            <td>{{$p->id}}</td>
                            <td>{{$p->name}}</td>
                            <td>{{$p->budget}}</td>
                            <td>{{$p->deadline}}</td>
                            <td><span>{{$participants_c}}</span><a href="projects/{{$p->id}}/participants/add" style="float: right">Add</a></td>
                            <td style="text-align: center" class='{{$OnlyAdmin}}'><a href="projects/edit/{{$p->id}}">Edit</a>
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
        $('#example1').DataTable()
        $('#example2').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    })

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