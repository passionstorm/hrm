<?php $arrSession = explode('-',$setting->workTime) ?>

@extends('layout.index')

@section('css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="datetimepicker/bootstrap-datetimepicker.min.css">
    <style>
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
        .flex-o2{
            display: flex;
        }
        a.tab-o1{
            color: blue;
            padding: 10px;
            display: inline-block;
            margin: 0;
            border-bottom: 1px solid blue;
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
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f0"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-primary btn-block reset-btn" id="r0" type="button">Reset</button>
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
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f1"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-primary btn-block reset-btn" id="r1" type="button">Reset</button>
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
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="text" id="dtpStart" class="form-control i-o1 f2" placeholder="start" name="startDT" autocomplete="off">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-arrow-right"></i></span>
                                            <input type="text" id="dtpEnd" class="form-control i-o1 f2" placeholder="end" name="endDT" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f2"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-primary btn-block reset-btn" id="r2" type="button">Reset</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-success btn-block" type="submit">Submit</button>
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


<script src="datetimepicker/moment.min.js"></script>
<script src="datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function(){
        //bootstrap datetimepicker
        {
            $('#dtpStart, #dtpEnd').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                minDate: new Date(),
                stepping: 15,
                useCurrent: false
            });
            $("#dtpStart").on("dp.change", function (e) {
                $('#dtpEnd').data("DateTimePicker").minDate(new Date(e.date._d.getTime()+15*60*1000));
            });
            $("#dtpEnd").on("dp.change", function (e) {
                $('#dtpStart').data("DateTimePicker").maxDate(new Date(e.date._d.getTime()-15*60*1000));
            });
        }
        //end-bootstrap datetimepicker

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
                $('.startDate').change(function(){
                    $('.endDate').datepicker('setStartDate', $(this).datepicker('getDate') );
                })
                $('.endDate').change(function(){
                    $('.startDate').datepicker('setEndDate', $(this).datepicker('getDate') );
                })
            }
            //end-Date range setting

            //limited date
            {
                $('.rangeDate').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    orientation: 'bottom',
                });
                $('.startDate').change(function(){
                    if($(this).val() && $('.endDate').val()){
                        $('.rangeDate, #spForV').removeAttr('disabled');
                        updateData(arrV);
                    }
                    $('.rangeDate').datepicker('setStartDate', $(this).datepicker('getDate') );
                })
                $('.endDate').change(function(){
                    if($(this).val() && $('.endDate').val()){
                        $('.rangeDate, #spForV').removeAttr('disabled');
                        updateData(arrV);
                    }
                    $('.rangeDate').datepicker('setEndDate', $(this).datepicker('getDate') );
                })
            }
            //end-limited date

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
            $('.startTimepicker, .endTimepicker').timepicker({
                showMeridian: false,
                minuteStep: 15,
                defaultTime: false,
                snapToStep: true,
            });
            
        }
        //end-bootstrap-timepicker

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
                    $('#dtpEnd, #dtpStart').data("DateTimePicker").minDate(new Date());
                    $('#dtpEnd, #dtpStart').data("DateTimePicker").maxDate(false);
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

        //session picker
        {
            
        }
        //end-session picker

        //test
        {
            $('#test').click(function(){
                let today = 11;
                let tomorrow = today;
                tomorrow++;

                console.log( today);
                console.log( tomorrow);

                // let today = {a:1,b:2};
                // let tomorrow = today;
                // tomorrow.a = 3;

                // console.log( today.a);
                // console.log( tomorrow.a);
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