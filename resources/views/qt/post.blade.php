@extends('layout.index')

@section('css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
    <style>
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
                <a data-toggle="tab" href="#menu1" class="tab-o1 a-active">Session</a>
                <a data-toggle="tab" href="#menu2" class="tab-o1">All day</a>
                <a data-toggle="tab" href="#menu3" class="tab-o1">Several days</a>
                <span style="flex-grow: 2"></span>
            </div>
            <div class="tab-content">
                <div id="menu1" class="tab-pane fade in active">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div style="width: 70%">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker i-o1 f2 hasSetDate" autocomplete="off" required name="dayForSession">
                                        </div>
                                    </div>
                                </div>     
                                <div class="row mgt15">
                                    <div class="col-md-6 mrb-o1">
                                        <select class="form-control sessionPicker f2" name="session">
                                            <?php $arrSession = explode('-',$setting->workTime) ?>
                                            @foreach ($arrSession as $item)
                                                @if($item == Constants::MORNING_SESSION)
                                                    <option value="{{Constants::MORNING_SESSION}}">Morning</option>
                                                @elseif($item == Constants::AFTERNOON_SESSION)
                                                    <option value="{{Constants::AFTERNOON_SESSION}}">Afternoon</option>
                                                @elseif($item == Constants::EVENING_SESSION)
                                                    <option value="{{Constants::EVENING_SESSION}}">Evening</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input disabled name="start" type="text" class="form-control timepicker1 i-o1" autocomplete="off">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-arrow-right"></i></span>
                                            <input disabled name="end" type="text" class="form-control timepicker2 i-o1" autocomplete="off">
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
                <div id="menu2" class="tab-pane fade">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div style="width: 70%">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-primary"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker i-o1 f3 hasSetDate" autocomplete="off" required name="allDay">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f3"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-primary btn-block reset-btn" id ="r3" type="button">Reset</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-success btn-block" type="submit">Submit</button>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </form>                             
                </div>
                <div id="menu3" class="tab-pane fade">
                    <form action="qt/post" method="post">
                        @csrf
                        <div class="flex-pcenter">
                            <div style="width: 70%">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker startDate i-o1 f4" placeholder="from..." name="startDate" autocomplete="off">
                                            <div class="input-group-addon bg-primary"><i class="fa fa-arrow-right"></i></div>
                                            <input type="text" class="form-control datepicker endDate i-o1 f4" placeholder="to..." name="endDate" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mgt15">
                                        <textarea rows="1" name="comment" placeholder="Your comment..." class="form-control f4"></textarea>
                                    </div>
                                </div>  
                                <div class="row mgt15">
                                    <div class="col-xs-6">
                                        <button class="btn btn-primary btn-block reset-btn" id="r4" type="button">Reset</button>
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
            let today = new Date();
            let tomorrow = new Date();
            tomorrow.setDate( tomorrow.getDate() + 1);
            $('.hasSetDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: today
            });
            $('.hasSetDate').datepicker('setDate', today);
            //Date range setting
            {
                $('.startDate').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    startDate: today
                })
                $('.endDate').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    startDate: tomorrow
                })
                $('.startDate').change(function(){
                    let startDate2 = $(this).datepicker('getDate');
                    startDate2.setDate( startDate2.getDate() + 1 ); 
                    $('.endDate').datepicker('setStartDate', startDate2 );
                })
                $('.endDate').change(function(){
                    let endDate1 = $(this).datepicker('getDate');
                    endDate1.setDate( endDate1.getDate() - 1 ); 
                    $('.startDate').datepicker('setEndDate', endDate1 );
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

        //time input
        {
            var s_time = 0;
            var maxShortOutTime = <?php echo $setting->vacation ?>;
            $('#minus-s-time').click(function(){
                s_time -= 15;
                if(s_time < 0){
                    s_time = 0;
                }
                $('#s-time').val(s_time + ' minutes');
            })
            $('#plus-s-time').click(function(){
                s_time += 15;
                if(s_time > maxShortOutTime){
                    s_time = 120;
                }
                $('#s-time').val(s_time + ' minutes');
            })
        }
        //end-time input

        //reset form
        {
            $('.reset-btn').click(function(){
                let id = $(this).attr('id').substr(1);
                let date = new Date();
                let m = date.getMonth() + 1 +'';
                let d = date.getDate() +'';
                let ymd = date.getFullYear() + '-' + m.padStart(2,'0') + '-' + d.padStart(2,'0');
                if(id == 2){
                    $('input[name="dayForSession"]').val( ymd );
                    $('.sessionPicker').val( $('.sessionPicker').find('option:first').val() );
                    $('.sessionPicker').trigger('change');
                    $( 'textarea.f'+id ).val('');
                }else if(id == 3){
                    $('input[name="allDay"]').val( ymd );
                    $( 'textarea.f'+id ).val('');
                }else{
                    $( '.f' + id ).val('');
                }
            })
        }
        //end-reset form

        //session picker
        {
            const MORNING_SESSION = <?php echo json_encode(Constants::MORNING_SESSION) ?>;
            const AFTERNOON_SESSION = <?php echo json_encode(Constants::AFTERNOON_SESSION) ?>;
            const EVENING_SESSION = <?php echo json_encode(Constants::EVENING_SESSION) ?>;
            var arrSession = <?php echo json_encode( explode('-',$setting->workTime) ) ?>;
            var x = [];
            var y = [];
            arrSession.forEach((e)=>{
                e = e.replace('h',':');
                y.push(e)
                if(y.length == 3){
                    x.push(y);
                    y = [];
                }
            })
            arrSession = x;
            var sessionName = $('.sessionPicker').val();
            arrSession.forEach((e)=>{
                if(e[0] == sessionName){
                    $('.timepicker1').timepicker({
                        showMeridian: false,
                        defaultTime: e[1]
                    });
                    $('.timepicker2').timepicker({
                        showMeridian: false,
                        defaultTime: e[2]
                    });
                }
            })
            $('.sessionPicker').change(function(){
                var sessionName = $('.sessionPicker').val();
                arrSession.forEach((e)=>{
                    if(e[0] == sessionName){
                        $('.timepicker1').val(e[1]);
                        $('.timepicker2').val(e[2]);
                    }
                })
            })
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