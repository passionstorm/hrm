
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
        #shiftPicker{
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
                        <input type="text" id="LEEDate" name="endDate" style="display: none">
                        <input type="text" name="startTime" id="LESTime" style="display: none">
                        <input type="text" name="endTime" id="LEETime" style="display: none">
                        <div class="flex-pcenter">
                            <div  class="f-w-o1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" id="LESDate" class="form-control i-o1 f0 hasSetDate" autocomplete="off" required name="startDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mgt15">
                                    <div class="col-md-6">
                                        <select class="form-control f0" id="shiftPicker" name="shift">
                                            @foreach($shifts as $s)
                                                <option value="{{$s->id}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 rmgt-o1">
                                        <select name="type" id="typePicker" class="form-control f0">
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
                                            <input type="text" style="text-align: center" class="form-control f0" placeholder="How long..." id="minutePicker" name="time" required autocomplete="off">
                                            <div class="input-group-btn">
                                                <button class="btn btn-success" type="button" id="pBtn">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-default btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
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
                        <input type="text" name="type" value="{{Constants::OUT_VACATION}}" style="display: none">
                        <input type="text" id="OEDate" name="endDate" style="display: none">
                        <div class="flex-pcenter">
                            <div  class="f-w-o1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" id="OSDate" class="form-control hasSetDate i-o1 f1" autocomplete="off" required name="startDate">
                                        </div>
                                    </div>
                                    <div class="col-md-6 rmgt-o1">
                                        <div class="input-group">
                                            <input type="text" class="form-control startTimepicker i-o1 f1" placeholder="start" name="startTime" required autocomplete="off">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-arrow-right"></i></span>
                                            <input type="text" class="form-control endTimepicker i-o1 f1" placeholder="end" name="endTime" required autocomplete="off">
                                        </div>
                                        <span id="spent"></span>
                                    </div>
                                </div>     
                                <div class="row c-comment1">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f1 comment" autocomplete="off"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-default btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
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
                                        <input type="text" id="vSDate" class="form-control i-o1 f2 noSetDate" placeholder="Start date" name="startDate" autocomplete="off" required>
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="text" id="vEDate" class="form-control i-o1 f2 noSetDate" placeholder="End date" name="endDate" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <div class="dropdown">
                                            <input class="dropdown-toggle form-control i-o1 f2" id ="vSTime" data-toggle="dropdown" placeholder="Start time" autocomplete="off" name="startTime" required>
                                            <ul class="dropdown-menu dropdown-menu-o1">
                                                @foreach($shifts as $s)
                                                    <li class="mr-b-5">
                                                        <a title="{{ substr($s->start,0,5) }}" style="cursor:default"  class="pd-lr-20 optionSTime">Start of {{$s->name}}</a>
                                                    </li>
                                                @endforeach
                                                <li class="virusInput">
                                                    <input type="text" class="form-control cStartTime  mr-b-8 f2" placeholder="Your custom time" autocomplete="off" name="cStartTime">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="dropdown">
                                            <input class="dropdown-toggle form-control i-o1 f2" id ="vETime" data-toggle="dropdown" placeholder="End time" autocomplete="off" name="endTime" required>
                                            <ul class="dropdown-menu dropdown-menu-o1">
                                                @foreach($shifts as $s)
                                                    <li class="mr-b-5">
                                                        <a title="{{ substr($s->end,0,5) }}" style="cursor:default"  class="pd-lr-20 optionETime">End of {{$s->name}}</a>
                                                    </li>
                                                @endforeach
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
                                            <select name="type" id="rSelect2" class="form-control" required>
                                                <option value="" disabled selected style="display: none">Your reason...</option>
                                                @foreach($dynamicReason as $vr)
                                                    <option value="{{$vr->id}}">{{$vr->reason}}</option>
                                                @endforeach
                                                <option  value="{{Constants::OTHER_VACATION}}">Other...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row c-comment2" style="display: none">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f2 comment" autocomplete="off"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-default btn-block" type="button" onclick="window.location.href='qt/list'">Back</button>
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
    var ymd = <?php echo json_encode(explode('-', date('Y-m-d'))) ?>.join('-');
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
            $('.hasSetDate, .noSetDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: new Date(),
                enableOnReadonly: false ,
                orientation: 'bottom',
            });
            $('.hasSetDate').datepicker('setDate', new Date());

            //hidden input
            {
                $('#LEEDate, #OEDate').val( ymd );
                $('#LESDate').change(function(){
                    $('#LEEDate').val($(this).val());
                });
                $('#LESDate').change(function(){
                    $('#LEEDate').val($(this).val());
                });
                $('#OSDate').change(function(){
                    $('#OEDate').val($(this).val());
                });
            }
            //end-hidden input

            //Date range setting
            {
                let date = new Date();
                $('#vSDate').change(function(){
                    if($(this).datepicker('getDate') == null){
                        $('#vEDate').datepicker('setStartDate', date);
                    }else{
                        $('#vEDate').datepicker('setStartDate', $(this).datepicker('getDate') );
                    }
                })
                $('#vEDate').change(function(){
                    if($(this).datepicker('getDate') == null){
                        $('#vSDate').datepicker('setEndDate', false);
                    }else{
                        $('#vSDate').datepicker('setEndDate', $(this).datepicker('getDate') );
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
                $('#vSTime, #vETime, .cStartTime, .cEndTime').keypress(function(e) {
                    e.preventDefault();
                });
                $('.optionSTime').click(function(){
                    $('#vSTime').val($(this).attr('title')).trigger('change');
                });
                $('.cStartTime').change(function(){
                    $(this).val( formatTime( $(this).val() ) );
                    $('#vSTime').val($(this).val()).trigger('change');
                });
                $('.optionETime').click(function(){
                    $('#vETime').val($(this).attr('title')).trigger('change');
                });
                $('.cEndTime').change(function(){
                    $(this).val( formatTime( $(this).val() ) );
                    $('#vETime').val($(this).val()).trigger('change');
                });
            }
            //end-setting for vacation time
        }
        //end-bootstrap-timepicker

        //comment picker
        {
            $('select[id^="rSelect"]').change(function(){
                let id = $(this).attr('id').substr(7);
                if($(this).val() == {{Constants::OTHER_VACATION}}){
                    $('.c-comment'+id).css('display', 'block').find('textarea');
                }else{
                    $('.c-comment'+id).css('display', 'none');
                }
            });
        }
        //end-comment picker

        //minute picker
        {
            var step = <?php echo $setting->hour_step?>*60;
            var maxTime = <?php echo $setting->short_leave ?>*60;
            var value = 0;
            $('#minutePicker').keypress(function(e) {
                e.preventDefault();
            });
            $('#pBtn').click(function(){
                if(value >= maxTime){
                    return ;
                }
                value += step;
                $('#minutePicker').val(value + ' minutes').trigger('change');
            });
            $('#mBtn').click(function(){
                if(value <= step){
                    return ;
                }
                value -= step;
                $('#minutePicker').val(value + ' minutes').trigger('change');
            });
            $('#minutePicker, #typePicker, #shiftPicker').change(function(){
                if(!$('#minutePicker').val()){
                    return ;
                }
                let time = Number($('#minutePicker').val().replace(' minutes', ''));
                let shifts = <?php echo $shifts ?>;
                let shiftId = $('select[name="shift"]').val();
                let reasonId = $('select[name="type"]').val();
                shifts.forEach((shift)=>{
                    if(shift.id != shiftId){
                        return ;
                    }
                    if(reasonId == {{Constants::LATE_VACATION}}){
                        let shiftStartTime = shift.start.substr(0, 5).split(':');
                        shiftStartTime = Number(shiftStartTime[0])*60 + Number(shiftStartTime[1]);
                        let endTime = shiftStartTime + time;
                        endTime = String(Math.floor(endTime/60)).padStart(2, '0') + ':' + String(endTime%60).padStart(2, '0');
                        $('#LESTime').val(shift.start.substr(0, 5));
                        $('#LEETime').val(endTime);
                    }else if(reasonId == {{Constants::EARLY_VACATION}}){
                        let shiftEndTime = shift.end.substr(0, 5).split(':');
                        shiftEndTime = Number(shiftEndTime[0])*60 + Number(shiftEndTime[1]);
                        let startTime = shiftEndTime - time;
                        startTime = String(Math.floor(startTime/60)).padStart(2, '0') + ':' + String(startTime%60).padStart(2, '0');
                        $('#LESTime').val(startTime);
                        $('#LEETime').val(shift.end.substr(0, 5));
                    }
                });
            });
        }
        //end-minute picker

        //count spent time
        {
            //go out
            {
                $('.startTimepicker, .endTimepicker').change(function(){
                    $('#sBtnf0').attr('disabled', 'disabled');
                    $('.startTimepicker, .endTimepicker').css('color', 'black');
                    $(this).val( formatTime( $(this).val() ) );
                    let start = $('.startTimepicker').val() + ':00';
                    let end = $('.endTimepicker').val() + ':00';
                    if( start.length >= 4 && end.length >= 4 ){
                        let nodes = <?php echo $shifts ?>;
                        let x = 0;
                        nodes.forEach((e)=>{
                            if(start >= e.start && start <= e.end && end >= e.start && end <= e.end){
                                x++;
                            }
                        });
                        if(!x){
                            $('#spent').text('You must enter time in shift range!').css('color', 'red');
                            $('.startTimepicker, .endTimepicker').css('color', 'red');
                            return ;
                        }
                        let arrStart = start.split(':');
                        let arrEnd = end.split(':');
                        let spent = ( Number(arrEnd[0]) + Number(arrEnd[1])/60 )-( Number(arrStart[0]) + Number(arrStart[1])/60 );
                        let shortLeave = <?php echo $setting->short_leave ?>;
                        let hoursStep = <?php echo $setting->hour_step ?>;
                        if(spent > shortLeave || spent < hoursStep){
                            $('#spent').text('Invalid time, please try again!').css('color', 'red');
                            $('.startTimepicker, .endTimepicker').css('color', 'red');
                            return ;
                        }
                        $('#spent').text('Out in '+spent+' hours').css('color', 'green');
                        $('#sBtnf0').removeAttr('disabled');
                    }
                });
            }
            //end-go out
            
            //vacation
            {
                $('#vSDate, #vEDate, #vSTime, #vETime').change(function(){
                    $('#btnfv').attr('disabled', 'disabled');
                    $('#vSTime, #vETime').css('color', 'black');
                    if(  $('#vSDate').val() && $('#vEDate').val() && $('#vSTime').val() && $('#vETime').val() ){
                        let startTime = $('#vSTime').val() + ':00';
                        let endTime = $('#vETime').val() + ':00';
                        let nodes = <?php echo $shifts ?>;
                        let x = 0;
                        nodes.forEach((e)=>{
                            if(startTime >= e.start && startTime <= e.end && endTime >= e.start && endTime <= e.end){
                                x += 2;
                            }else if( 
                                (startTime >= e.start && startTime <= e.end) 
                                || (endTime >= e.start && endTime <= e.end) 
                            ){
                                x++;
                            }
                        });
                        if(x < 2){
                            $('#vSpent').text('You must enter time in shift range!').css('color', 'red');
                            $('#vSTime, #vETime').css('color', 'red');
                            return ;
                        }
                        if($('#vSDate').val() == $('#vEDate').val() && startTime >= endTime){
                            $('#vSTime, #vETime').css('color', 'red');
                            $('#vSpent').text('Invalid data. Please try another!').css('color', 'red');
                            return ;
                        }
                        $('#btnfv').removeAttr('disabled');
                        $('#vSTime, #vETime').css('color', '');
                        $('#vSpent').text('Checking...').css('color', 'blue');
                        //ajax
                        {
                            let startV = $('#vSDate').val()+' '+$('#vSTime').val()+':00';
                            let endV = $('#vEDate').val()+' '+$('#vETime').val()+':00';
                            $.ajax({
                                url: 'qt/ajax/handlingVacation',
                                method: 'get',
                                dataType: 'json',
                                data:{
                                    start: startV,
                                    end: endV
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
                });
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

    /**
        *change string time to format HH:MM:SS
        *@param String
        *@return String
    */
    function formatTime(rawTime){
        if(rawTime.substr(rawTime.length - 1, 1) == ':'){
            rawTime = rawTime + '00';
        };
        if(rawTime.length == 4){
            rawTime = '0'+rawTime;
        };
        return rawTime;
    }

    function autosize() {
        var el = this;
        setTimeout(function () {
            el.style.cssText = 'height:auto';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
        });
    }
</script>
@endsection