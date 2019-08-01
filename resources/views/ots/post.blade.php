@extends('layout.index')

@section('css')
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<style>
  .content {
    min-height: 0 !important;
  }

  textarea {
    overflow: hidden;
    width: 100%;
    display: block;
    padding: 10px;
    resize: none;
  }

  #s-d {
    background-color: white !important
  }

  .o-shadow {
    box-shadow: 3px 3px 5px grey;
  }

  .d-label {
    display: none;
  }

  .s-text {
    font-weight: bold !important;
  }

  .fs-label {
    font-size: 100%;
    margin-left: 10px
  }

  .ctn-fbtn {
    display: flex;
    justify-content: flex-end;
  }

  .modal-dialog {
    position: absolute;
    top: 50% !important;
    transform: translate(0, -50%) !important;
    -ms-transform: translate(0, -50%) !important;
    -webkit-transform: translate(0, -50%) !important;
    margin: auto 5%;
    width: 90%;
    height: 30%;
  }

  .modal-content {
    min-height: 100%;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
  }

  .modal-header {
    display: flex !important;
    justify-content: space-between !important;
  }

  .modal-header:before,
  .modal-header:after {
    content: none;
  }

  .modal-body {
    position: absolute;
    top: 45px;
    /** height of header **/
    bottom: 45px;
    /** height of footer **/
    left: 0;
    right: 0;
    overflow-y: auto;
  }

  .modal-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
  }

  .n-display {
    display: none
  }

  .c-flex1 {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
  }

  .e1 {
    flex-basis: 100%;
  }

  .e2,
  .e3 {
    width: 100px
  }

  .e3 {
    margin-left: 20px
  }
  #th2{
    width: 40%;
  }
  #th3, #th4{
    width: 5%;
  }

  @media only screen and (min-width:992px) {
    #th2{
      width: 70%;
    }
    #th3, #th4{
      width: 5%;
    }
    .e1 {
      flex-basis: 50%;
    }

    .e2,
    .e3 {
      margin-left: 20px
    }

    .modal-dialog {
      top: 50% !important;
      left: 30% !important;
      width: 40%;
      height: 25%;
    }
  }
</style>
@endsection

@section('content')
<?php
  $o_select = 'selected';
  $edit = '';
  $is_approved = '';
  $e_v = '';
  $s_label = 'd-label';
  $amount = 0;
  $today = date('Y-m-d');
  $display = '';
  $data = [
        (object) [
          'id' => '',
          'start' => '',
          'end' => '',
          'project_id' => '',
          'name' => '',
          'comment' => '',
        ]
  ];
  if( isset($item) ){
    $e_v = 'n-display';
    $data = $item;
    $amount = count($data);
  }
  if($date != $today){
    $display =  '; display: none';
  }

  if( isset($approved) ){
    if( $approved == Constants::APPROVED_OT ){
      $edit = 'disabled';
      $is_approved = 'Approved';
      $s_label = 'label-success';
    }elseif($approved == Constants::PENDDING_OT){
      $is_approved = 'Pendding';
      $s_label = 'label-primary';
    }elseif($approved == Constants::REJECT_OT){
      $is_approved = 'Reject';
      $s_label = 'label-danger';
    }elseif($approved == Constants::DRAFT_OT){
      $is_approved = 'Draft';
      $s_label = 'label-default';
    }
  }

?>
<div class="content-wrapper">
  <section class="content-header">
    <div id="displayAlert"></div>
    <div class="box">
      <h3 style="margin-left:10px"><span>OT in </span><span class="ot-date">{{$date}} </span><span
          class="label {{$s_label}}">{{$is_approved}}</span></h3>
      <div class="box-body no-padding">
        <table class="table table-hover">
          <tr>
            <th>Time</th>
            <th id="th2">Project</th>
            <th id="th3"></th>
            <th id="th4"></th>
          </tr>
          <?php $totalTime = 0 ?>
          @foreach ($data as $i)
          <?php $totalTime += ( strtotime($i->end)-strtotime($i->start) )/3600 ?>
          @if ($i->start)
          <tr id="ot-item{{$i->id}}">
            <td><b>{{$i->start}}</b> - <b>{{$i->end}}</b></td>
            <td>{{$i->name}}</td>
            <td><a href="#" onclick="event.preventDefault()" id="e-ot{{$i->id}}" style="{{$display}}"><i class="fa fa-edit"></i></a></td>
            <td><a href="#" onclick="event.preventDefault()" id="r-ot{{$i->id}}" style="{{$display}}"><i class="fa fa-remove" style="color:red"></i></a></td>
          </tr>
          @else
          <tr>
            <td>Empty</td>
          </tr>
          @endif
          @endforeach
          <tr>
            <td colspan="4">Total of ot time: {{$totalTime}} hours</td>
          </tr>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
  </section>
  <section class="content">
    <div>
      @include('forms.PostOTs')
    </div>
  </section>
  {{-- <button id="test">test</button> --}}
  {{-- <p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p> --}}
</div>
@endsection

@section('script')
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>
  var f_approved = <?php echo Constants::PENDDING_OT ?>;
  $(document).ready(function(){

    //setting to be able to use post ajax
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    //setting to be able to use post ajax

    //page is always at the top after reload
    setTimeout(function(){
      window.scrollTo(0,0)
    },100)
    //end-page is always at the top after reload

    //Timepicker
    $('body').on('focus', '.timepicker', function(){
      $(this).timepicker({
      showMeridian: false,
      defaultTime: false
      })
    })
    //end-Timepicker

    //Web strorage-check message
    if( sessionStorage.getItem('message') == 'success' ){
      $('#displayAlert').removeClass().addClass('alert alert-success').html('<h4><i class="icon fa fa-check"></i>Save successfully</h4>');
      sessionStorage.removeItem('message');
    }
    //end-Web strorage-check message

    var x=0;
    var data = <?php echo json_encode($data); ?>;
    <?php $arr = explode('-', $date); $y = $arr[0]; $m = $arr[1]; $d = $arr[2] ?>;
    let y = <?php echo $y ?>, m = <?php echo $m ?>, d = <?php echo $d ?>;
    var date = y +'-'+ m.toString().padStart(2, '0') +'-'+ d.toString().padStart(2, '0');

    $('[id^=e-ot]').click(function(){
      var id = $(this).attr('id').substr(4);
      var v = '-e'+id;
      $('[id^=e-ot], [id^=r-ot], #add').css('display', 'none');
      $('.save-btn, #reset').css('display', 'inline-block');
      dynamic_field(v);
      data.forEach(function(e){
        if(e.id == id){
          $('#start'+v).val(e.start);
          $('#end'+v).val(e.end);
          $('#ta'+v).text(e.comment);
          $('#select'+v+' option[value="'+e.project_id+'"]').attr('selected', 'selected');
        }
      });
      animateScroll();
      var el = document.querySelector('#ta'+v);
      el.addEventListener('keydown', autosize);
    });

    $('[id^=r-ot]').click(function(){
      var id = $(this).attr('id').substr(4);
      $('[id=ot-item'+id+']').css('background-color', 'red');
      $('#e-ot'+id+', #r-ot'+id+' ,#add').css('display', 'none');
      $('.save-btn, #reset').css('display', 'inline-block');
      for(var i = 0; i<data.length; i++){
        if(data[i].id == id){
          data.splice(i,1);
        }
      }
    });

    $('#reset').click(function(){
      data = <?php echo json_encode($data); ?>;
      $('[id^=ot-item]').css('background-color', '');
      $('.save-btn, #reset').css('display', 'none');
      $('[id^=e-ot], [id^=r-ot]').css('display', 'inline');
      $('#add').css('display', 'inline-block');
      $('[id^=oBox]').remove();
    });

    $(document).on('click', '#add', function(){
      dynamic_field(x);
      animateScroll();
      $('.save-btn, #reset').css('display', 'inline-block');
      $('[id^=e-ot], [id^=r-ot]').css('display', 'none');
      var el = document.querySelector('#ta'+x);
      el.addEventListener('keydown', autosize);
      x++;
    });

    $(document).on('click', '.remove-btn', function(){
      var btn_id = $(this).attr('id');
      $('#oBox'+btn_id+'').remove();
      if( $('[id^=oBox]').length == 0 ){
        $('.save-btn').css('display', 'none');
        var v = 0;
        $('[id^=ot-item]').each(function(){
          var id = $(this).attr('id').substr(7);
          if( $(this).css('background-color') == 'rgb(255, 0, 0)' ){
            v++;
          }else{
            $('#e-ot'+id+', #r-ot'+id).css('display', 'inline');
          }
        });
        if(v == 0){
          $('#reset').css('display', 'none');
          $('#add').css('display', 'inline-block');
        }
      }
    });

    $('#s-d').click(function(e){
      e.preventDefault();
      f_approved = <?php echo Constants::REJECT_OT ?>;
      $('input[type="submit"]').trigger('click');
    });

    $('#dynamic_form').on('submit', function(e){
      e.preventDefault();
      $('#displayAlert, #displayAlert1').removeClass().html('');

      //fix id
      if( $('[id^="oBox-e"]').length == 0 ){
        $('[id^="oBox"]').each(function(index){
          var cId = 'oBox' + index;
          $(this).attr('id', cId);
        });
        $('input[name="start[]"]').each(function(index){
          var cId = 'start' + index;
          $(this).attr('id', cId);
        });
        $('input[name="end[]"]').each(function(index){
          var cId = 'end' + index;
          $(this).attr('id', cId);
        });
      }
      //end-fix id

      if( $('[id^="oBox-e"]').length ){//edit ot
        let id = $('[id^="oBox-e"]').attr('id').substr(6);
        for(let i=0;i<data.length;i++){
          if(data[i].id == id){
            data[i].project_id = parseInt( $('#select-e'+id).val() );
            data[i].start = $('#start-e'+id).val();
            data[i].end = $('#end-e'+id).val();
            data[i].comment = $('#ta-e'+id).val();
          }
        }
      }//end-edit ot
      else{//add ot
        let i = 0, id = 0, obj = {};
        $('#dynamic_form').serializeArray().forEach(function(e){
          if(i == 0){
            obj = {};
          }
          if(e.name == 'project_id'){
            obj[e.name] = parseInt(e.value);
          }else{
            obj[e.name] = e.value;
          }
          i++;
          if(i == 4){
            if( !data[0].id ){
              data=[];
            }
            obj['id'] = 'oBox'+id;
            data.push(obj);
            i = 0;id++;
          }
        });
      }//end-add ot

      //AJAX
      $.ajax({
        url: 'ot/post',
        method: 'post',
        dataType: 'json',
        data: {
          data: data,
          date: date,
          approved: f_approved
        },
        error: function(xhr, ajaxOptions, thrownError){
           console.log(xhr.status);
           console.log(xhr.responseText);
           console.log(thrownError);
        },
        success: function(rdata){
          $('input[type="text"]').css('color', 'black');
          if(rdata.samePosts){
            $errorSamePosts = rdata.samePosts; 
            $('#displayAlert1').removeClass().addClass('alert alert-danger').html('<h4>- You can\'t enter same posts</h4>');
            if($errorSamePosts.length > 0){
              $errorSamePosts.forEach( (e) => {
                $('#start'+e+', #end'+e).css('color', 'red');
              });
            }
            data = <?php echo json_encode($data); ?>;
            animateScrollAlert();
          }
          if(rdata.errorTimes){
            $('#displayAlert1').removeClass().addClass('alert alert-danger').html('<h4>- Date must be greater or equal today</h4> <h4>- End must by greater than start</h4>');
            $errorTimes = rdata.errorTimes;
            $errorTimes.forEach( (e) => {
              if(e.substr(0, 4) != 'oBox'){
                e = 'oBox-e'+e;
              }
              $('#'+e+' input[name="start"], #'+e+' input[name="end"]').css('color', 'red');
            });
            data = <?php echo json_encode($data); ?>;
            animateScrollAlert();
          }
          if(rdata.existOT){
            $existOT = rdata.existOT;
            $('#displayAlert1').removeClass().addClass('alert alert-danger').html('<h4>- Your ot time is conflict</h4>');
            $existOT.forEach( (e) => {
              if(e.substr(0,4) != 'oBox'){
                e = 'oBox-e'+e;
              }                                                             
              $('#'+e+' input[name="start"], #'+e+' input[name="end"]').css('color', 'red');
            });
            data = <?php echo json_encode($data); ?>;
            animateScrollAlert();
          }
          if(rdata.success){
              sessionStorage.setItem('message', 'success');location.reload();
          }
        }
      });
      //end-AJAX

      f_approved = 0;
    });

    $('#test').click(function(){
    })

  });

  function dynamic_field(i){
    $('.ccc').append('<div id="oBox'+i+'" class="box box-primary"> <div class="box-body" style="padding-bottom: 20px"> <div class="ctn-fbtn"> <button type="button" class="btn btn-danger remove-btn" id="'+i+'"><i class="fa fa-remove"></i></button> </div> <div class="c-flex1"> <div class="e1"> <div class="form-group"> <label for="">Project</label> <select id="select'+i+'" name="project_id" class="form-control"> @foreach($projects as $project) <option value="{{$project->id}}">{{$project->name}}</option> @endforeach </select> </div> </div><div class="e2"> <div class="form-group"> <label for="">Start</label> <input required id="start'+i+'" name="start" type="text" class="form-control timepicker" autocomplete="off"> </div> </div><div class="e3"> <div class="form-group"> <label for="">End</label> <input required id="end'+i+'" name="end" type="text" class="form-control timepicker" autocomplete="off"> </div> </div> </div> <div style="display:flex;justify-content: space-between;"></div><textarea rows="1"  id="ta'+i+'" name="comment" placeholder="Your comment" class="form-control"></textarea> </div> </div>');
  }

  function autosize() {
      var el = this;
      setTimeout(function () {
          el.style.cssText = 'height:auto';
          el.style.cssText = 'height:' + el.scrollHeight + 'px';
      });
  }
  
  function animateScroll(){
    var position = $('div.ccc > div:last-child').offset().top;
    $('html').animate({ scrollTop: position }, 1000);
  }

  function animateScrollAlert(){
    var position = $('#displayAlert1').offset().top - 60;
    $('html').animate({ scrollTop: position }, 1000);
  }

</script>
@endsection