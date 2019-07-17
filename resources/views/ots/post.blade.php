@extends('layout.index')

@section('css')
<style>
.n-display{
  display: none
}
</style>
@endsection

@section('content')
<?php
  $o_select = 'selected';
  $e_v = '';
  $data = (object) [
    'date' => '',
    'start' => '',
    'end' => '',
    'project_id' => '',
    'comment' => '',
  ];
  if( isset($item) ){
    $e_v = 'n-display';
    $data = $item;
  }
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      @if( isset($item) )
      {{'Edit OT'}}
      @else
      {{'Add OT'}}
      @endif
    </h1>
  </section>
  <section class="content">
    <div style="padding: 0 40px">
      
        @include('messages.success')
        @include('forms.PostOTs')
      
    </div>
  </section>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    //Web strorage-check message
    if( sessionStorage.getItem('message') == 'success' ){
      $('#displayAlert').removeClass().addClass('alert alert-success').html('<h4><i class="icon fa fa-check"></i>Save successfully</h4>');
      sessionStorage.removeItem('message');
    }
    //end-Web strorage-check message

    var x=0;
    dynamic_field(x);
    function dynamic_field(i){
      if(i<1){
        $('.ccc').append('<div id="oBox'+i+'" class="box box-primary"> <div class="box-body" style="padding-bottom: 20px"> <div class="ctn-fbtn" style="text-align: right"> <button type="button" class="btn btn-danger remove-btn {{$e_v}}" id="'+i+'"><i class="fa fa-remove"></i></button> </div> <div style="display:flex;justify-content: space-between;"> <div style="width: 48%"> <div class="form-group"> <label for="">Date</label> <input id="date'+i+'" name="date[]" type="date" class="form-control" value="{{$data->date}}" required> </div> </div> <div style="width: 48%"> <div class="form-group"> <label for="">Project</label> <select name="project[]" class="form-control" style="width: 100%;"> @foreach($projects as $project) <option value="{{$project->id}}" @if($project->id == $data->project_id){{$o_select}}@endif>{{$project->name}}</option> @endforeach </select> </div> </div> </div> <div style="display:flex;justify-content: space-between;"> <div style="width: 48%"> <div class="form-group"> <label for="">Start</label> <input id="start'+i+'" required name="start[]" type="time" class="form-control" value="{{$data->start}}"> </div> </div> <div style="width: 48%"> <div class="form-group"> <label for="">End</label> <input id="end'+i+'" required name="end[]" type="time" class="form-control" value="{{$data->end}}"> </div> </div> </div> <br> <textarea rows="4" class="form-control" name="comment[]" placeholder="Your comment">{{$data->comment}}</textarea> </div> </div>');
      }else{
        $('.ccc').append('<div id="oBox'+i+'" class="box box-primary"> <div class="box-body" style="padding-bottom: 20px"> <div style="text-align: right"> <button type="button" class="btn btn-danger remove-btn" id="'+i+'"><i class="fa fa-remove"></i></button> </div> <div style="display:flex;justify-content: space-between;"> <div style="width: 48%"> <div class="form-group"> <label for="">Date</label> <input id="date'+i+'" name="date[]" type="date" class="form-control" value="{{$data->date}}" required> </div> </div> <div style="width: 48%"> <div class="form-group"> <label for="">Project</label> <select name="project[]" class="form-control" style="width: 100%;"> @foreach($projects as $project) <option value="{{$project->id}}" @if($project->id == $data->project_id){{$o_select}}@endif>{{$project->name}}</option> @endforeach </select> </div> </div> </div> <div style="display:flex;justify-content: space-between;"> <div style="width: 48%"> <div class="form-group"> <label for="">Start</label> <input id="start'+i+'" required name="start[]" type="time" class="form-control" value="{{$data->start}}"> </div> </div> <div style="width: 48%"> <div class="form-group"> <label for="">End</label> <input id="end'+i+'" required name="end[]" type="time" class="form-control" value="{{$data->end}}"> </div> </div> </div> <br> <textarea rows="4" class="form-control" name="comment[]" placeholder="Your comment">{{$data->comment}}</textarea> </div> </div>');
      }
    }

    $(document).on('click', '#add', function(){
      x++;
      dynamic_field(x);
    });
    $(document).on('click', '.remove-btn', function(){
      var btn_id = $(this).attr('id');
      $('#oBox'+btn_id+'').remove();
    });
    $('#dynamic_form').on('submit', function(e){
      e.preventDefault();
      //fix id
      $('input[type=date]').each(function(index){
        var cId = 'date' + index;
        $(this).attr('id', cId);
      })
      $('input[name="start[]"]').each(function(index){
        var cId = 'start' + index;
        $(this).attr('id', cId);
      })
      $('input[name="end[]"]').each(function(index){
        var cId = 'end' + index;
        $(this).attr('id', cId);
      })
      //end-fix id
      $fValue = $('#dynamic_form').serialize();
      var pathname = window.location.pathname;
      var post_id = pathname.substring( pathname.lastIndexOf('/') + 1 );
      if(post_id != 'post'){
        $fValue = $fValue.concat('&id='+post_id);
        var v = <?php echo json_encode($data); ?>;
        var date2 = $('input[name="date[]"]').val();
        var start2 = $('input[name="start[]"]').val();
        var end2 = $('input[name="end[]"]').val();
        var project2 = $('select[name="project[]"]').val();
        var comment = $('textarea[name="comment[]"]').val();
        if(date2 == v.date && start2 == v.start && end2 == v.end && project2 == v.project_id && comment == v.comment){
          $('.displayWarning').css('display', 'none');
          $('.displaySuccess').css('display', 'none');
          $('.displayExistOT').css('display', 'none');
          $('.displayEditNotChange').css('display', 'block');
          $('.displaySamePost').css('display', 'none');
        }else{
          if(date2 != v.date){
            $fValue = $fValue.concat('&changedOt='+'y');
          }
          if(start2 != v.start || end2 != v.end || project2 != v.project_id || comment != v.comment){
            $fValue = $fValue.concat('&changedOtDetail='+'y');
          }
          if(date2 == v.date && start2 == v.start && end2 == v.end && (project2 != v.project_id || comment != v.comment) ){
            $fValue = $fValue.concat('&ignoreConflictTime='+'y');
          }
        }
      }
      //AJAX
      $.post('ot/post', $fValue, function(data){
        $('input[type=date], input[type=time]').css('color', 'green');
        if(data.samePosts){
          $errorSamePosts = data.samePosts; 
          $('#displayAlert').removeClass().addClass('alert alert-danger').html('<h4>- You can\'t enter same posts</h4>');
          if($errorSamePosts.length > 0){
            $errorSamePosts.forEach( (e) => {
              $('#date' + e ).css('color', 'red');
              $('#start' + e ).css('color', 'red');
              $('#end' + e ).css('color', 'red');
            });
          }
        }
        if(data.errorDates){
          $('#displayAlert').removeClass().addClass('alert alert-danger').html('<h4>- Date must be greater or equal today</h4> <h4>- End must by greater than start</h4>');
          $('#' + data.errorDates).css('color', 'red');
        }
        if(data.errorTimes){
          $('#displayAlert').removeClass().addClass('alert alert-danger').html('<h4>- Date must be greater or equal today</h4> <h4>- End must by greater than start</h4>');
          $errorTimes = data.errorTimes;
          $errorTimes.forEach( (e) => {
            $('#' + e).css('color', 'red');
          });
        }
        if(data.existOT){
          $existOT = data.existOT;
          $('#displayAlert').removeClass().addClass('alert alert-danger').html('<h4>- Your ot time is conflict</h4>');
          $existOT.forEach( (e) => {
            $('#date' + e).css('color', 'red');
            $('#start' + e).css('color', 'red');
            $('#end' + e).css('color', 'red');
          })
        }
        if(data.success){
          if(post_id != 'post'){
            $("[id^='oBox']").remove();
            sessionStorage.setItem('message', 'success');
            location.reload();
          }else{
            $("[id^='oBox']").remove();
            dynamic_field(0);
            x=0;
            $('#displayAlert').removeClass().addClass('alert alert-success').html('<h4><i class="icon fa fa-check"></i>Save successfully</h4>');
          }
        }
      });
      //end-AJAX
    });
  });
</script>
@endsection