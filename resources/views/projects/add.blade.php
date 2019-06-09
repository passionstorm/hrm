@extends('layout.index')

@section('css')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add project
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div style="padding: 0 40px">
      <!-- general form elements -->
      <div class="box box-primary">
        @include('messages.errors')
        @include('forms.AddProject')
      </div>
      <!-- /.box -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
  $(function () {

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

  })
</script>
@endsection