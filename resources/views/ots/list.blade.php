@extends('layout.index')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<style>
    #spc:after {
        content: none;
    }

    .d-n {
        display: none !important;
    }

    .iga-1 {
        border-right: 1px solid #d2d6de !important;
        padding-left: 6px !important;
        padding-right: 6px !important;
    }

    .pd-r-15 {
        padding-right: 15px
    }

    td {
        border-bottom: 2px solid #D5DCD4 !important;
    }

    tr:hover {
        cursor: pointer;
        background-color: #D5DCD4 !important;
    }

    td.mini {
        display: none;
    }

    td.date {
        width: 15%;
    }

    td.time {
        width: 15%;
    }

    td.arr {
        width: 5%;
    }

    .mgt {
        margin-top: 7px
    }

    .mgt2 {
        margin-top: 14px;
    }

    .cbtn {
        display: block;
        width: 100%;
    }

    td.arr {
        text-align: right
    }

    td.appr {
        text-align: right;
        width: auto;
    }

    @media only screen and (min-width:992px) {
        td.mini {
            display: table-cell;
        }

        td.date {
            width: 10%;
        }

        td.time {
            width: 10%;
        }

        td.appr {
            text-align: right;
            width: 15%;
        }

        .cbtn {
            display: inline-block;
            width: auto;
        }

        .mgt,
        .mgt2 {
            margin-top: 0;
        }
    }
</style>
@endsection

@section('content')
<?php 
 $totalTimeOT = 0;
 foreach($ots as $i){
     if($i->approved == 1){
        $totalTimeOT += $i->ot_t;
     }
 }
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('messages.success')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            List of your OTs
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <form id="ot_search">
                            <div class="row">
                                <div class="mgt col-md-3">
                                    <select name="project" id="" class="form-control" required>
                                        <option value="0" selected>All projects</option>
                                        @foreach ($projects as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mgt col-md-3">
                                    <input name="month_year" type="month" class="form-control" value={{$yearMonth}}
                                        max="{{$yearMonth}}" required>
                                </div>
                                <div class="mgt2 col-md-1">
                                    <button class="btn btn-primary cbtn">Search</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <p><b>&nbsp;Approved OT: </b><span id="totalTimeOT">{{$totalTimeOT}}</span> hours</p>
                        <table class="table table-hover">
                            <tbody>
                                @for ($i = $today; $i >= 1; $i--)
                                <?php
                                        $y = str_pad($i, 2, 0, STR_PAD_LEFT);
                                        $date = $yearMonth.'-'.$y;
                                        $monthDate = substr($date,-5,5);
                                        $is_approved = '';
                                        $ot_t = 0;
                                        foreach($ots as $x){
                                            $d = substr($x->ot_date,-2,2);
                                            if($y == $d){
                                                $ot_t += $x->ot_t;
                                                if( $x->approved == 1 ){
                                                    $is_approved = 'Approved';
                                                }
                                            }
                                        }
                                    ?>
                                <tr onclick="window.location='#';">
                                    <td class="date">{{$monthDate}}</td>
                                    <td class="time">
                                        @if($ot_t == 0)
                                        {{'...'}}
                                        @else
                                        {{$ot_t}} h
                                        @endif
                                    </td>
                                    <td class="mini {{$date}}"><span id="holder{{$y}}">...</span></td>
                                    <td class="appr">
                                        @if($is_approved == 'Approved')
                                        <span class="label label-success">{{$is_approved}}</span>
                                        @endif
                                    </td>
                                    <td class="arr">
                                        <i class="fa fa-angle-right" style="font-size:24px"></i>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- page script -->
<script>
    $(document).ready(function(){
        var x = <?php echo json_encode($ots); ?>;
        addOTTime(x);

        $('#ot_search').submit(function(e){
            e.preventDefault();
            //make data for AJAX request
            var v = $('input[name="month_year"]').val();
            var va = v.split('-');
            var month = va[1];
            var year = va[0];
            var project = $('select[name="project"]').val();
            //end-make data for AJAX request
            //AJAX
            $.ajax({
                url: 'ot/list/ajax',
                type: 'get',
                dataType: 'json',
                data: {
                    month: month,
                    year: year,
                    project: project
                },
                success: function(data){
                    $('tbody').remove();
                    $('table').append('<tbody></tbody>')
                    if(data.ots.length == 0){
                        data.ots = [
                            {
                                ot_date: '',
                                approved: '',
                                startToEnd: '',
                                ot_t: ''
                            }
                        ]
                    }
                    var totalTimeOT = 0;
                    data.ots.forEach( (e) => {
                        if(e.approved == 1){
                            totalTimeOT += parseFloat(e.ot_t);
                        }
                    })
                    totalTimeOT = totalTimeOT.toFixed(1);
                    for(var i = data.daysOfMonth; i>=1; i--){
                        var y = String(i).padStart(2,'0');
                        var date = $('input[type="month"]').val() + '-' + y;
                        var monthDate = date.substr(-5,5);
                        var is_approved = '';
                        var ot_t = 0;
                        data.ots.forEach( (e) => {
                            var v = e.ot_date.substr(-2,2);
                            if(y == v){
                                ot_t += parseFloat(e.ot_t);
                                if(e.approved == 1){
                                    is_approved = 'Approved';
                                }
                            }
                        })
                        if( ot_t == 0){
                            ot_t = '...';
                        }else{
                            ot_t += ' h';
                        }
                        $('tbody').append('<tr onclick="window.location=\'#\';"> <td class="date">'+monthDate+'</td> <td class="time">'+ot_t+'</td> <td class="mini '+date+'"><span id="holder'+y+'">...</span></td> <td class="appr"><span class="label label-success">'+is_approved+'</span></td> <td class="arr"> <i class="fa fa-angle-right" style="font-size:24px"></i> </td> </tr>');
                    }
                    $('#totalTimeOT').text(totalTimeOT);
                    addOTTime(data.ots);
                }
            })
            //end-AJAX
        })

        $('#test').click(function(){
            
        });
    });

    function addOTTime(array){
        array.forEach( (e) =>{
            $('[id=holder'+e.ot_date.substr(-2,2)+']').remove();
            if(e.ot_date){
                $('.'+e.ot_date).append('<span class="label label-primary" style="margin-right:10px">'+e.startToEnd+'</span>');
            }
        })
    }

</script>

@endsection