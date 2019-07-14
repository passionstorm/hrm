@extends('layout.index')

@section('css')
<style>
  .displaySuccess,
  .displayWarning,
  .displayExistOT,
  .displaySamePost {
    display: none
  }
</style>
@endsection

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      @if( isset($user) )
      {{'Edit OT'}}
      @else
      {{'Add OT'}}
      @endif
    </h1>
  </section>
  <section class="content">
    <div style="padding: 0 40px">
      <div class="box box-primary">
        @include('messages.errors')
        @include('messages.success')
        @include('forms.PostOTs')
      </div>
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
    var x=0;
    dynamic_field(x);
    function dynamic_field(i){
      if(i<1){
        $('.box-body').append('<div class="oBox'+i+'"><div class="row" id="row'+i+'"><br> <div class="col-md-3"> <div class="input-group"> <div class="input-group-addon"> <span><b>Date</b></span> </div> <input id="date'+i+'" name="date[]" type="date" class="form-control" required> </div> </div> <div class="col-md-2"> <div class="input-group"> <div class="input-group-addon"> <span><b>Start</b></span> </div> <input id="start'+i+'" required name="start[]" type="time" class="form-control"> </div> </div> <div class="col-md-2"> <div class="input-group"> <div class="input-group-addon"> <span><b>End</b></span> </div> <input id="end'+i+'" required name="end[]" type="time" class="form-control"> </div> </div> <div class="col-md-3"> <div class="input-group"><div class="input-group-addon"> <span><b>Project</b></span> </div> <select name="project[]" class="form-control" style="width: 100%;"> @foreach($projects as $project) <option value="{{$project->id}}">{{$project->name}}</option> @endforeach </select> </div> </div> <div class="col-md-1"> <button type="button" class="btn btn-primary" id="add">Add More</button> </div> </div><br><textarea rows="4" cols="50" placeholder="Your comment" name="comment[]" value="no comment"></textarea></div>');
      }else{
        $('.box-body').append('<div class="oBox'+i+'"><div class="row" id="row'+i+'"> <br> <br> <div class="col-md-3"> <div class="input-group"> <div class="input-group-addon"> <span><b>Date</b></span> </div> <input id="date'+i+'" name="date[]" type="date" class="form-control" required> </div> </div> <div class="col-md-2"> <div class="input-group"> <div class="input-group-addon"> <span><b>Start</b></span> </div> <input id="start'+i+'" required name="start[]" type="time" class="form-control"> </div> </div> <div class="col-md-2"> <div class="input-group"> <div class="input-group-addon"> <span><b>End</b></span> </div> <input id="end'+i+'" required name="end[]" type="time" class="form-control"> </div> </div> <div class="col-md-3"> <div class="input-group"><div class="input-group-addon"> <span><b>Project</b></span> </div> <select name="project[]" class="form-control" style="width: 100%;"> @foreach($projects as $project) <option value="{{$project->id}}">{{$project->name}}</option> @endforeach </select> </div> </div> <div class="col-md-1"> <button type="button" class="btn btn-danger remove-btn" id="'+i+'">X</button> </div> </div><br><textarea rows="4" cols="50" placeholder="Your comment" name="comment[]" value="no comment"></textarea></div>');
      }

    }

    $(document).on('click', '#add', function(){
      x++;
      dynamic_field(x);
    });
    $(document).on('click', '.remove-btn', function(){
      var btn_id = $(this).attr('id');
      $('.oBox'+btn_id+'').remove();
    });
    $('#dynamic_form').on('submit', function(e){
      e.preventDefault();
      $fValue = $('#dynamic_form').serialize();
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
      $.post('ot/post', $fValue, function(data){
        $('input[type=date], input[type=time]').css('color', 'green');
        if(data.samePosts){
          $errorSamePosts = data.samePosts;
          $('.displayWarning').css('display', 'none');
          $('.displaySuccess').css('display', 'none');
          $('.displayExistOT').css('display', 'none');
          $('.displaySamePost').css('display', 'block');
          if($errorSamePosts.length > 0){
            $errorSamePosts.forEach( (e) => {
              $('#date' + e ).css('color', 'red');
              $('#start' + e ).css('color', 'red');
              $('#end' + e ).css('color', 'red');
            });
          }
        }
        if(data.errorDates){
          $('.displayWarning').css('display', 'block');
          $('.displaySuccess').css('display', 'none');
          $('.displayExistOT').css('display', 'none');
          $('.displaySamePost').css('display', 'none');
          $('#' + data.errorDates).css('color', 'red');
        }
        if(data.errorTimes){
          $errorTimes = data.errorTimes;
          $('.displayWarning').css('display', 'block');
          $('.displaySuccess').css('display', 'none');
          $('.displayExistOT').css('display', 'none');
          $('.displaySamePost').css('display', 'none');
          $errorTimes.forEach( (e) => {
            $('#' + e).css('color', 'red');
          });
        }
        if(data.existOT >= 0){
          $existOT = data.existOT;
          $('.displayWarning').css('display', 'none');
          $('.displaySuccess').css('display', 'none');
          $('.displaySamePost').css('display', 'none');
          $('.displayExistOT').css('display', 'block');
          $('#date' + $existOT ).css('color', 'red');
          $('#start' + $existOT ).css('color', 'red');
          $('#end' + $existOT ).css('color', 'red');
        }
        if(data.success){
          $("[class^='oBox']").remove();
          dynamic_field(0);
          x=0;
          $('.displayWarning').css('display', 'none');
          $('.displayExistOT').css('display', 'none');
          $('.displaySamePost').css('display', 'none');
          $('.displaySuccess').css('display', 'block');
        }
      });
      
    });
    
    //test
    // $('#test').click(function(){
    //   var v = $('input[name="start[]"]').val();
    //   console.log(v);
    // });

  });
</script>
@endsection