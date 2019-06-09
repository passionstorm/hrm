@extends('layout.index')

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