<?php
$spent = number_format(($vacation-$time_remaining)/$vacation*100,0);
?>

@extends('layout.index')

@section('css')
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style> 
    .mgt-10{
      margin-top: 10px;
    }
    .bg-primary{
      background-color: #337ab7;
    }
    section.content-header>h3{
      margin-left: 10px;
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      align-content: stretch;
    }
    @media only screen and (min-width:992px){
      .r-w-o1{
        width: 25%;
      }
    }
  </style>
@endsection

@section('content')
<div class="content-wrapper">
  {{-- <button id="test">test</button> --}}
  <section class="content-header">
      <h3><span>Vacation</span><button class="btn btn-primary"><a href="qt/post" style="color: white">New plan</a></button></h3>
  </section>
  <section class="content">
    <div class="box box-primary">
        <div style="padding: 10px 20px">
          <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Time remaining</span>
              <span class="info-box-number" id="timeRemaining">
                {{$time_remaining}} @if(abs($time_remaining)>1){{'minutes'}}@else{{'minute'}}@endif
              </span>
              <div class="progress">
                <div class="progress-bar" style="width: {{$spent}}%"></div>
              </div>
              <span class="progress-description">
                Spent <span class="spent-percent">{{$spent}}</span>%
              </span>
            </div>
          </div>
          <p style="padding-right: 50px">Pendding: {{$pendding}} <a href="qt/list/pendding">View more</a></p>
          <div class="row">
            <div class="box box-info">
              <div class="box-header with-border">
                <form id="searchByDate">
                  <div class="row mgt-10">
                    <div class="col-xs-8 col-sm-4">
                    <input name="date" type="text" class="form-control" id="datepicker"  autocomplete="off" placeholder="Choose date" value="{{$today}}" required >
                    </div>
                    <div class="col-xs-4">
                      <button class="btn btn-primary">Search</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table class="table no-margin">
                    <thead>
                      <tr>
                        <th class="r-w-o1" >Start</th>
                        <th class="r-w-o1">End</th>
                        <th>Spent</th>
                        <th>Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($history as $i)
                          <tr>
                            <td>{{explode(' ', $i->start)[1]}}</td>
                            <td>{{explode(' ', $i->end)[1]}}</td>
                            <td>{{$i->spent}} minutes</td>
                            <td>
                              @foreach (Constants::VACATION_TYPE as $key => $item)
                                  @if($i->type == $key)
                                    {{$item}}
                                  @endif
                              @endforeach
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </section>
</div>
@endsection

@section('script')
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
  $(document).ready(function(){
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayBtn: 'linked',
        todayHighlight: true,
    })

    $('#searchByDate').submit(function(e){
      e.preventDefault();
      $.ajax({
        url:'qt/ajax/searchByDate',
        method:'get',
        data:$('#searchByDate').serialize(),
        dataType:'json',
        error: function(xhr, ajaxOptions, thrownError){
           console.log(xhr.status);
           console.log(xhr.responseText);
           console.log(thrownError);
        },
        success: function(data){
          $('table>tbody tr').remove();
          var arr = data.data;
          arr.forEach((e)=>{
            var vacationType = <?php echo json_encode(Constants::VACATION_TYPE) ?>;
            var type = '';
            for(key in vacationType){
              if(e.type == key){
                type = vacationType[key];
                break;
              }
            }
            $('table>tbody').append('<tr> <td>'+e.start.split(' ')[1]+'</td> <td>'+e.end.split(' ')[1]+'</td> <td>'+e.spent+' minutes</td> <td>'+type+'</td> </tr>')
          });
        }
      });
    })

    //test
    $('#test').click(function(){
    });
    //end-test
  });
</script>
@endsection
