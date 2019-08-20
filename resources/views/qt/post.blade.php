<?php 
$rawArrSession = explode('-',$setting->workTime);
$arrSession = [];
foreach($rawArrSession as $ras){
    $d = DB::table('session')->find($ras);
    array_push($arrSession, $d->name, $d->start, $d->end);
}
?>

@extends('layout.index')

@section('css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
    <style>
        .mgr-t-o1{
            margin-top: 15px;
        }
        .g-c{
            display: grid;
            grid-template-columns: 100%;
        }
        .virusInput{
            padding: 0 20px;
        }
        .pd-0{
            padding: 0 !important
        }
        .mr-b-5{
            margin-bottom: 5px !important; 
        }
        .mr-b-8{
            margin-bottom: 8px !important; 
        }
        .pd-lr-20{
            padding: 8px 20px !important
        }
        .dropdown-menu-o1{
            border: 1px solid #d2d6de;
            border-radius: 0;
            width: 100%;
        }
        .btn-o1{
            background-color: white;
            border: 1px solid #d2d6de;
            border-radius: 0;
            color: #a9a9a9;
            text-align: center;
        }
        .btn-o1:hover{
            color: #a9a9a9;
        }
        .f-w-o1{
            width: 90%;
        }
        .cn-n{
            border-radius: 0;
        }
        #sessionPicker{
            color: green;
            padding-left: 10px;
        }
        .flex-o3{
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
        }
        .flex-o3>div{
            flex-basis: 100%;
        }
        .flex-o4{
            display: flex;
            flex-wrap: wrap;
        }
        .input-o1{
            text-align: center;
        }
        .rmgt-o1{
            margin-top: 10px;
        }
        .w-70{
            width: 70px;
        }
        .btn01{
            display: block !important
        }
        .mgt15{
            margin-top: 15px
        }
        textarea {
            overflow: hidden;
            width: 100%;
            display: block;
            padding: 10px;
            resize: none;
        }
        .i-o1{
            text-align: center
        }
        .bg-primary{
            background-color: #3c8dbc !important;
            color: white;
        }
        a.tab-o1{
            color: blue;
            padding: 10px;
            display: inline-block;
            margin: 0;
            border-bottom: 1px solid blue;
        }
        .flex-o2{
            display: flex;
        }
        div.flex-o2>span{
            border-bottom: 1px solid blue;
        }
        a.a-active{
            color: black;
            border-top: 1px solid blue; 
            border-left: 1px solid blue; 
            border-right: 1px solid blue; 
            border-bottom: none;
        }
        .flex-pcenter{
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        .tab-content{
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .mrb-o1{
            margin-bottom: 10px
        }
        @media only screen and (min-width: 992px){
            .f-w-o1{
                width: 70%;
            }
            .rmgt-o1{
                margin-top: 0;
            }
            .flex-o3>div:first-child{
                flex-basis: 50%;
            }
            .flex-o3>div:last-child{
                flex-basis: 100%;
            }
            .mgr-t-o1{
            margin-top: 0px;
            }
            .g-c{
                display: grid;
                grid-template-columns: 47.5% 47.5%;
                grid-column-gap: 5%;
            }
            .c-vStartTime{
                grid-area: 2/ 1/ 3 / 2;
            }
            .c-endDate{
                grid-area: 1/ 2/ 2 / 3;
            }
            .c-startDate{
                grid-area: 1/ 1/ 2 / 2;
            }
            .c-vEndTime{
                grid-area: 2/ 2/ 3 / 3;
            }
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
  {{-- <button id="test">test</button> --}}
  <section class="content-header">
      <h3 style="margin-left:10px"><span>Vacation</span></h3>
  </section>
  <section class="content">
    <div class="box box-primary">
        <div style="padding: 10px 20px">
            <div class="flex-o2">
                <a data-toggle="tab" href="#menu0" class="tab-o1 a-active">Late/Early</a>
                <a data-toggle="tab" href="#menu1" class="tab-o1">Go out</a>
                <a data-toggle="tab" href="#menu2" class="tab-o1">Vacation</a>
                <span style="flex-grow: 2"></span>
            </div>
            <div class="tab-content">
                <div id="menu0" class="tab-pane fade in active">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div  class="f-w-o1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker i-o1 f0 LEDate" autocomplete="off" required name="LEDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mgt15">
                                    <div class="col-md-6">
                                        <select class="form-control sessionPicker f0" name="session">
                                            @for($i = 0; $i < count($arrSession); $i+=3)
                                                <option value="{{$arrSession[$i]}}">{{$arrSession[$i]}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 rmgt-o1">
                                        <select name="type" id="" class="form-control f0">
                                            <option value="{{Constants::LATE_VACATION}}">Come late</option>
                                            <option value="{{Constants::EARLY_VACATION}}">leave early</option>
                                        </select>
                                    </div>
                                </div>      
                                <div class="row mgt15">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button" id="mBtn">
                                                <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" style="text-align: center" class="form-control f0" placeholder="How long..." id="vI" name="time" required autocomplete="off">
                                            <div class="input-group-btn">
                                                <button class="btn btn-success" type="button" id="pBtn">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row c-comment0">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your reason..." class="form-control f0 comment" autocomplete="off"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-success btn-block" type="submit">Submit</button>
                                    </div>
                                </div>    
                            </div>
                        </div>                   
                    </form>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div  class="f-w-o1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker dayForOut i-o1 f1" autocomplete="off" required name="dayForOut">
                                        </div>
                                    </div>
                                    <div class="col-md-6 rmgt-o1">
                                        <div class="input-group">
                                            <input type="text" class="form-control startTimepicker i-o1 f1" placeholder="start" name="start" required autocomplete="off">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-arrow-right"></i></span>
                                            <input type="text" class="form-control endTimepicker i-o1 f1" placeholder="end" name="end" required autocomplete="off">
                                        </div>
                                        <span id="spent"></span>
                                    </div>
                                </div>     
                                <div class="row c-comment1">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your reason..." class="form-control f1 comment" autocomplete="off"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-success btn-block" type="submit" id="sBtnf0">Submit</button>
                                    </div>
                                </div>    
                            </div>
                        </div>                   
                    </form>
                </div>
                <div id="menu2" class="tab-pane fade">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div class="f-w-o1">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input type="text" id="dStart" class="form-control i-o1 f2 startDate" placeholder="Start date" name="startD" autocomplete="off" required>
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="text" id="dEnd" class="form-control i-o1 f2 endDate" placeholder="End date" name="endD" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <div class="dropdown">
                                            <input class="dropdown-toggle form-control vStartTime i-o1 f2" data-toggle="dropdown" placeholder="Start time" autocomplete="off" name="vStartTime" required>
                                            <ul class="dropdown-menu dropdown-menu-o1">
                                                @for($i = 0; $i < count($arrSession); $i+=3)
                                                    <li class="mr-b-5">
                                                        <a title="{{ substr($arrSession[$i+1],0,5) }}" style="cursor:default"  class="pd-lr-20 optionSTime">Start of {{$arrSession[$i]}}</a>
                                                    </li>
                                                @endfor
                                                <li class="virusInput">
                                                    <input type="text" class="form-control cStartTime  mr-b-8 f2" placeholder="Your custom time" autocomplete="off" name="cStartTime">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="dropdown">
                                            <input class="dropdown-toggle form-control vEndTime i-o1 f2" data-toggle="dropdown" placeholder="End time" autocomplete="off" name="vEndTime" required>
                                            <ul class="dropdown-menu dropdown-menu-o1">
                                                @for($i = 0; $i < count($arrSession); $i+=3)
                                                    <li class="mr-b-5">
                                                        <a title="{{substr($arrSession[$i+2],0,5)}}" style="cursor:default" class="pd-lr-20 optionETime">End of {{$arrSession[$i]}}</a>
                                                    </li>
                                                @endfor
                                                <li class="virusInput">
                                                    <input type="text" class="form-control cEndTime mr-b-8 f2" placeholder="Your custom time" autocomplete="off" name="cEndTime">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <span id="vSpent"></span>
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <div class="dropdown">
                                            <select name="rSelect" id="rSelect2" class="form-control" required>
                                                <option value="" disabled selected style="display: none">Your reason...</option>
                                                @foreach(DB::table('reason')->where('companyId', Auth::user()->companyId)->select('reason', 'id')->get() as $vr)
                                                    <option value="{{$vr->id}}">{{$vr->reason}}</option>
                                                @endforeach
                                                <option  value="0">Other...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row c-comment2" style="display: none">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your reason..." class="dropdown-toggle form-control f2 comment" data-toggle="dropdown" autocomplete="off">Other reason...</textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-success btn-block" type="submit" id="btnfv">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>         
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
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script>
    $(document).ready(function(){

        //bootstrap tab setting
        {
            $('a.tab-o1').click(function(){
                $('a.tab-o1').each(function(){
                    $(this).removeClass('a-active');
                });
                $(this).addClass('a-active');
            });
        }
        //end-bootstrap tab setting

        //Date picker
        {   
            let tomorrow = new Date();
            let nextTomorrow = new Date();
            tomorrow.setDate( tomorrow.getDate() + 1);
            nextTomorrow.setDate( nextTomorrow.getDate() + 2);
            $('.hasSetDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: tomorrow,
                orientation: 'bottom',
            });
            $('.hasSetDate').datepicker('setDate', tomorrow);
            $('.LEDate, .dayForOut, .startDate, .endDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: new Date(),
                enableOnReadonly: false ,
                orientation: 'bottom',
            });
            $('.LEDate, .dayForOut').datepicker('setDate', new Date());

            //Date range setting
            {
                let date = new Date();
                $('.startDate').change(function(){
                    if($(this).datepicker('getDate') == null){
                        $('.endDate').datepicker('setStartDate', date);
                    }else{
                        $('.endDate').datepicker('setStartDate', $(this).datepicker('getDate') );
                    }
                })
                $('.endDate').change(function(){
                    if($(this).datepicker('getDate') == null){
                        $('.startDate').datepicker('setEndDate', false);
                    }else{
                        $('.startDate').datepicker('setEndDate', $(this).datepicker('getDate') );
                    }
                })
            }
            //end-Date range setting
        }
        //end-Date picker

        //dynamic text area
        {
            var el = document.querySelectorAll('textarea');
            for (let i = 0; i < el.length; i++) {
                el[i].addEventListener('keydown', autosize);
            }
        }
        //end-dynamic text area

        //bootstrap-timepicker
        {
            $('.startTimepicker, .endTimepicker, .cStartTime, .cEndTime').timepicker({
                showMeridian: false,
                minuteStep: 15,
                defaultTime: false,
                snapToStep: true,
            });

            //setting for vacation time
            {
                $('.vStartTime, .vEndTime, .cStartTime, .cEndTime').keypress(function(e) {
                    e.preventDefault();
                });
                $('.optionSTime').click(function(){
                    $('.vStartTime').val($(this).attr('title')).trigger('change');
                });
                $('.cStartTime').change(function(){
                    var val = $(this).val();
                    if(val.substr(val.length - 1, 1) == ':'){
                        val = val + '00';
                    };
                    $(this).val(val);
                    if(val.length == 4){
                        val = '0'+val;
                    };
                    $(this).val(val);
                    $('.vStartTime').val($(this).val()).trigger('change');
                });
                $('.optionETime').click(function(){
                    $('.vEndTime').val($(this).attr('title')).trigger('change');
                });
                $('.cEndTime').change(function(){
                    var val = $(this).val();
                    if(val.substr(val.length - 1, 1) == ':'){
                        val = val + '00';
                    };
                    $(this).val(val);
                    if(val.length == 4){
                        val = '0'+val;
                    };
                    $(this).val(val);
                    $('.vEndTime').val($(this).val()).trigger('change');
                });
            }
            //end-setting for vacation time
        }
        //end-bootstrap-timepicker

        //comment picker
        {
            $('select[id^="rSelect"]').change(function(){
                let id = $(this).attr('id').substr(7);
                if($(this).val() == 0){
                    $('.c-comment'+id).css('display', 'block').find('textarea').val('Other reason...');
                }else{
                    $('.c-comment'+id).css('display', 'none');
                }
            });
        }
        //end-comment picker

        //time picker
        {
            var step = <?php echo $setting->minTimeForHandling ?>;
            var maxTime = <?php echo $setting->shortLeave ?>;
            var value = 0;
            $('#vI').keypress(function(e) {
                e.preventDefault();
            });
            $('#pBtn').click(function(){
                if(value >= maxTime){
                    return ;
                }
                value += step;
                $('#vI').val(value + ' minutes');
            });
            $('#mBtn').click(function(){
                if(value <= 0){
                    return ;
                }
                value -= step;
                if(value == 0){
                    $('#vI').val(value + ' minute');
                }else{
                    $('#vI').val(value + ' minutes');
                }
            });
        }
        //end-time picker

        //count spent time
        {
            //go out
            {
                $('.startTimepicker, .endTimepicker').change(function(){
                    $('.startTimepicker, .endTimepicker').css('color', 'black');
                    let start = $('.startTimepicker').val();
                    let end = $('.endTimepicker').val();
                    if( start.length >= 4 && end.length >= 4 ){
                        let arrStart = start.split(':');
                        let arrEnd = end.split(':');
                        let spent = ( arrEnd[0]*60 + Number(arrEnd[1]) )-( arrStart[0]*60 + Number(arrStart[1]) );
                        if(spent > 0 && spent < 120){
                            $('#spent').text('Out in '+spent+' minutes').css('color', 'green');
                            $('#sBtnf0').removeAttr('disabled');
                        }else{
                            $('#spent').text('Invalid time, please try again!').css('color', 'red');
                            $('.startTimepicker, .endTimepicker').css('color', 'red');
                            $('#sBtnf0').attr('disabled', 'disabled');
                        }
                    }else{
                        $('#sBtnf0').attr('disabled', 'disabled');
                    }
                });
            }
            //end-go out
            
            //vacation
            {
                //form validate
                    $('.startDate, .endDate, .vStartTime, .vEndTime').change(function(){
                        if(  $('.startDate').val() && $('.endDate').val() && $('.vStartTime').val() && $('.vEndTime').val() ){
                            if($('.startDate').val() == $('.endDate').val() && $('.vStartTime').val() >= $('.vEndTime').val()){
                                $('#btnfv').attr('disabled', 'disabled');
                                $('.vStartTime, .vEndTime').css('color', 'red');
                                $('#vSpent').text('Invalid data. Please try another!').css('color', 'red');
                            }else{
                                $('#btnfv').removeAttr('disabled');
                                $('.vStartTime, .vEndTime').css('color', '');
                                $('#vSpent').text('Checking...').css('color', 'blue');

                                //ajax
                                {
                                    var start = $('.startDate').val()+' '+$('.vStartTime').val()+':00';
                                    var end = $('.endDate').val()+' '+$('.vEndTime').val()+':00';
                                    $.ajax({
                                        url: 'qt/ajax/handlingVacation',
                                        method: 'get',
                                        dataType: 'json',
                                        data:{
                                            start: start,
                                            end: end
                                        },
                                        error: function(xhr, ajaxOptions, thrownError){
                                            console.log(xhr.status);
                                            console.log(xhr.responseText);
                                            console.log(thrownError);
                                        },
                                        success: function(data){
                                            let time = data.spent;
                                            $('#vSpent').text('You will spend '+time+' hours ').css('color', 'green');
                                        }
                                    });
                                }
                                //end-ajax
                            }
                        }
                    });
                //end-form validate
            }
            //end-vacation
        }
        //end-count spent time

        //reset form
        {
            $('.reset-btn').click(function(){
                let id = $(this).attr('id').substr(1);
                let date = new Date();
                let m = date.getMonth() + 1 +'';
                let d = date.getDate() +'';
                let ymd = date.getFullYear() + '-' + m.padStart(2,'0') + '-' + d.padStart(2,'0');
                if(id == 2){
                    $( '.f'+id ).val('');
                    $('.startDate, .endDate').datepicker('setStartDate', new Date());
                    $('.startDate, .endDate').datepicker('setEndDate', false);
                    $('#vSpent').text('').css('color', '');
                }else if(id == 1){
                    $('.dayForOut').datepicker('setDate', new Date());
                    $( 'input[name="start"], input[name="end"], textarea.f'+id ).val('');
                    $('#spent').text('');
                }else if(id == 0){
                    $('input[name="time"], textarea.f'+id).val('');
                    $('.LEDate').datepicker('setDate', new Date());
                    $('select.f0>option:first-child').attr('selected', 'selected')
                }
            })
        }
        //end-reset form

        //test
        {
            $('#test').click(function(){
                console.log( $('::placeholder').css('color') );
            })
        }
        //end-test

    })

    function autosize() {
        var el = this;
        setTimeout(function () {
            el.style.cssText = 'height:auto';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
        });
    }
</script>
@endsection