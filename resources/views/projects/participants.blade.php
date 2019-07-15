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

    .pd-r-15 {
        padding-right: 15px
    }
    [id*="remove"]{
        display: none;
    }
    .yes{
        background-color: #85ed7b;
    }
    .hidd{
        display: none;
    }
    .oshow{
        display: inline-block;
    }
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('messages.success')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add participants for project: {{$project_name}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <div class="row" style="margin-bottom: 20px">
                            <div class="pull-right  pd-r-15">
                                <a href="users/edit" class="btn btn-primary">New user</a>
                            </div>
                        </div>

                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">Id</th>
                                    <th width="80%">Name</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <?php
                                    $active = '';
                                    $hidd = '';
                                    $oshow = '';
                                    foreach($project_participants as $p){
                                        if($u->id == $p){
                                            $active = 'yes';
                                            $hidd = 'hidd';
                                            $oshow = 'oshow';
                                        }
                                    }
                                    ?>
                                    <td class="td{{$u->id}} {{$active}}">{{$u->id}}</td>
                                    <td class="td{{$u->id}} {{$active}}">{{$u->name}}</td>
                                    <td><button class="btn btn-primary {{$hidd}}" id="add{{$u->id}}" name="{{$u->id}}">Add</button></td>
                                    <td><button class="btn btn-danger {{$oshow}}" id="remove{{$u->id}}" name="{{$u->id}}">Remove</button></td>
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
    $(function() {
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

    $(document).ready(function(){
        $(document).on('click', '[id*="add"]', function(){
            $addValue = $(this).attr('name');
            $p_id = <?php echo $project_id; ?>;
            //AJAX
            $.ajax({
                url:'projects/participants/add/ajax',
                method:'get',
                data: {
                    'addValue': $addValue,
                    'p_id': $p_id
                },
                dataType: 'json',
                success: function(data){
                    var added_id = data.added_id;
                    $('.td'+added_id).css('background-color', '#85ed7b');
                    $('#add'+added_id).css('display', 'none');
                    $('#remove'+added_id).css('display', 'inline-block');
                }
            });
            //end-AJAX
        });
        $(document).on('click', '[id*="remove"]', function(){
            $removeValue = $(this).attr('name');
            $p_id = <?php echo $project_id; ?>;
            //AJAX
            $.ajax({
                url:'projects/participants/remove/ajax',
                method:'get',
                data: {
                    'removeValue': $removeValue,
                    'p_id': $p_id
                },
                dataType: 'json',
                success: function(data){
                    var removed_id = data.removed_id;
                    $('.td'+removed_id).css('background-color', '#f2d55e');
                    $('#add'+removed_id).css('display', 'inline-block');
                    $('#remove'+removed_id).css('display', 'none');
                }
            });
            //end-AJAX
        });
    });

</script>

@endsection