@extends('admin.layout.index')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<style>
    #spc:after{
        content: none;
    }
    .iga-1{
        border-right: 1px solid #d2d6de !important;
        padding-left: 6px !important;
        padding-right: 6px !important;
    }
    .input-group-addon{
        width: 0% !important;
    }
    .is_deleted{
      display: none
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
                  <span class="input-group-addon iga-1"><input type="checkbox" id="osau" checked></span>
                  <span class="input-group-addon"><b>Show only available users</b></span>
                </div>
              </div>
            </div>

            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Organization</th>
                <th>Salary</th>
                <th>Created_at</th>
                <th>is_deleted</th>
              </tr>
              </thead>
              <tbody>
                @foreach($users as $u)
                <tr class="@if($u->is_deleted){{"is_deleted"}}@endif">
                    <td>{{$u->id}}</td>
                    <td>{{$u->name}}</td>
                    <td>{{$u->organization}}</td>
                    <td>{{$u->salary}}</td>
                    <td>{{$u->created_at}}</td>
                    <td>
                      @if($u->is_deleted)
                        {!!'<span class="fa fa-check"></span>'!!}
                      @endif
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
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })

  //chuyen doi hien thi va ko hien thi is_deleted user
  $(document).ready(function(){
    $('#osau').change(function(){
      if(!$(this).is(':checked')){
        $('.is_deleted').css('display', 'table-row');
      }else{
        $('.is_deleted').css('display', 'none');
      }
    });
  });

</script>

@endsection