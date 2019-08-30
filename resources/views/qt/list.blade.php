<?php
use App\Http\Controllers\VacationController;
$qTController = new VacationController();
$aTRPercent = floor($aTimeRemaining / $vacation * 100);
$eTRPercent = floor($eTimeRemaining / $vacation * 100)
?>

@extends('layout.index')

@section('css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <style>
        .mr-b-0 {
            margin-bottom: 0 !important;
        }

        .pd-b-0 {
            padding-bottom: 0 !important;
        }

        .pd-tb-0 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .box-shadow-1 {
            box-shadow: 3px 3px 5px grey;
        }

        .p-lr-10 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .w-60 {
            width: 60px !important;
        }

        .noSidePad {
            padding-left: 0;
            padding-right: 0;
        }

        .h-xs {
            display: inline;
        }

        .h-md {
            display: none;
        }

        .r-d {
            display: none !important;
        }

        .tab-content {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .flex-o2 {
            display: flex;
        }

        div.flex-o2 > span {
            border-bottom: 1px solid blue;
        }

        a.a-active {
            color: black !important;
            border-top: 1px solid blue;
            border-left: 1px solid blue;
            border-right: 1px solid blue;
            border-bottom: none !important;
        }

        a.tab-o1 {
            color: blue;
            padding: 10px;
            display: inline-block;
            margin: 0;
            border-bottom: 1px solid blue;
        }

        .mgt-10 {
            margin-top: 10px;
        }

        .bg-primary {
            background-color: #337ab7;
        }

        section.content-header > h3 {
            margin-left: 10px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            align-content: stretch;
        }

        @media only screen and (min-width: 992px) {
            .noSidePad {
                padding: 10px 20px
            }

            .r-sidePad {
                padding: 10px 20px
            }

            .h-xs {
                display: none;
            }

            .h-md {
                display: inline-block;
            }

            .r-w-o1 {
                width: 25%;
            }

            .r-d {
                display: table-cell !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- <button id="test">test</button> --}}
        <section class="content-header">
            <h1>Vacation</h1>
        </section>
        <section class="content noSidePad">
            <div class="row f-c">
                <div class="col-xs-6 col-md-4">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Actual time off remaining</span>
                            <span class="info-box-number" id="timeRemaining">{{$aTimeRemaining}} Hours</span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Estimate time off remaining</span>
                            <span class="info-box-number" id="eTMH">{{$eTimeRemaining}} Hours</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="vacation/post" class="btn btn-primary pull-right">New plan</a>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Start</th>
                            <th>End</th>
                            <th>Spent</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($history as $i)
                            <?php
                            $spentTime = $qTController->VacationSpent((object)[
                                'start' => $i->start,
                                'end' => $i->end,
                            ]);
                            $start = str_replace(' ', '<br>', substr($i->start, 0, strlen($i->start) - 3));
                            $end = str_replace(' ', '<br>', substr($i->end, 0, strlen($i->end) - 3));
                            ?>
                            <tr>
                                <td>
                                    <span>{!! $start !!}</span>
                                </td>
                                <td>
                                    <span>{!! $end !!}</span>
                                </td>
                                <td>
                                    {{ $spentTime }} <span class="h-md">hours</span><span
                                            class="h-xs">h</span>
                                </td>
                                @if ($i->is_approved == Constants::APPROVED_VACATION)
                                    <td>
                                        <i class="fa fa-check h-xs" style="color:green"></i>
                                        <span class="label label-success h-md">Approved</span>
                                    </td>
                                @elseif ($i->is_approved == Constants::PENDING_VACATION)
                                    <td>
                                        <i class="fa fa-hourglass-start h-xs"></i>
                                        <span class="label label-default h-md">Pendding</span>
                                    </td>
                                @elseif ($i->is_approved == Constants::REJECTED_VACATION)
                                    <td>
                                        <i class="fa fa-ban h-xs" style="color:red"></i>
                                        <span class="label label-danger h-md">Reject</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </section>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {

            //bootstrap tab setting
            {
                $('a.tab-o1').click(function () {
                    $('a.tab-o1').each(function () {
                        $(this).removeClass('a-active');
                    });
                    $(this).addClass('a-active');
                });
            }
            //end-bootstrap tab setting

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                todayHighlight: true,
            })

            //not necessary for current template
            $('.searchByDate').change(function () {
                let date = $('.searchByDate').val();
                $.ajax({
                    url: 'qt/ajax/searchByDate',
                    method: 'get',
                    data: {
                        date: date
                    },
                    dataType: 'json',
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(xhr.responseText);
                        console.log(thrownError);
                    },
                    success: function (data) {
                        $('table>tbody tr').remove();
                        var arr = data.data;
                        arr.forEach((e) => {
                            var vacationType = <?php echo json_encode(Constants::VACATION_TYPE) ?>;
                            var type = '';
                            for (key in vacationType) {
                                if (e.type == key) {
                                    type = vacationType[key];
                                    break;
                                }
                            }
                            let hour_spent = (e.spent / 60);
                            $('table>tbody').append('<tr> <td>' + e.start.split(' ')[1] + '</td> <td>' + e.end.split(' ')[1] + '</td> <td>' + hour_spent + ' hours</td> <td>' + type + '</td> </tr>')
                        });
                    }
                });
            })
            //end-not necessary for current template

            //test
            $('#test').click(function () {
            });
            //end-test
        });
    </script>
@endsection