@extends('layout.index')

@section('css')
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<style>
  .iga-1{
      border-right: 1px solid #d2d6de !important;
      padding-left: 6px !important;
      padding-right: 6px !important;
  }
  .input-group-addon{
      width: 0% !important;
  }
  .d-n{
    display: none;
  }
  .d-grid{
    display: grid !important;
    grid-template-columns: 80% 5% !important;
  }
  .d-grid1{
    display: grid !important;
    grid-template-columns: 20% 20% 40% !important;
    justify-content: space-between !important;
  }
  .d-flex{
    display: flex;
    justify-content: flex-end;
  }

</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add OT
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div style="padding: 0 40px">

      <div class="box box-primary">
        @include('messages.errors')
        @include('messages.success')
        @include('forms.AddOT')
      </div>

  
      

      <!-- /.box -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

<!-- CK Editor -->
<script src="bower_components/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    //Time picker
    $('.timepicker').timepicker({
      showInputs: false
    })

    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')

  })

  $(document).ready(function(){
    $('#osau').change(function(){
      if($(this).is(':checked')){
        $('.d-n').css('display', 'block');
      }else{
        $('.d-n').css('display', 'none');
      }
    });

  });
</script>
@endsection