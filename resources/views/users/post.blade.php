@extends('layout.index')

@section('css')
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
      display: none !important;
    }
</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      @if( isset($user) )
        {{'Edit user'}}
      @else
        {{'Add user'}}
      @endif
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div style="padding: 0 40px">
      <!-- general form elements -->
      <div class="box box-primary">
        @include('messages.errors')
        @include('messages.success')
        @include('forms.PostUser')
      </div>
      <!-- /.box -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')

    {{-- checkbox để chuyển đổi giữa có và không hiển thị change password --}}
{{--     <script>
        $(document).ready(function(){
            $('#ChangePassword').change(function(){
                if( $(this).is(':checked') ){
                    $('.cont').removeAttr('hidden');
                }else{
                    $('.cont').attr('hidden', '');
                }
            });
        });
    </script> --}}

@endsection
