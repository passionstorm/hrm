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
</style>
@endsection

@section('content')
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
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col pull-right pd-r-15">
                                <a href="ot/post" class="btn btn-primary">Add OT</a>
                            </div>
                        </div>
                        <form id="ot_search">
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="project" id="" class="form-control" required>
                                        <option value="" selected disabled hidden>Choose Project...</option>
                                        <option value="0">All</option>
                                        @foreach ($projects as $key => $project)
                                        <option value="{{$key}}">{{$project}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input name="month_year" type="month" min="2019-01" value="2019-01"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Project</th>
                                    <th>Approved</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        {{-- <button id="test">test</button> --}}
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
        $('#ot_search').submit(function(e){
            e.preventDefault();
            $form_val = $('#ot_search').serialize();
            var v = $('input[name="month_year"]').val();
            var va = v.split('-');
            var month = va[1];
            var year = va[0];
            var project = $('select[name="project"]').val();
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
                        if(data.items){
                            $('tbody').remove();
                            $('table').append('<tbody></tbody>')
                            $items = data.items;
                            $amount = data.amount;
                            $items.forEach( (i) => {
                                console.log(i.id);
                                $('tbody').append('<tr> <td>'+i.id+'</td><td>'+i.date+'</td> <td>'+i.start+'</td> <td>'+i.end+'</td> <td>'+i.project+'</td> <td>'+i.approved+'</td> <td style="text-align: center"><a href="ot/post/'+i.id+'">Edit</a></td> </tr>');
                            });
                            $('tbody').append('<tr><td><b>Amount of OT: </b></td></td> <td >'+$amount+' hours</td> </tr>');
                        }
                    }
                })
            //end-AJAX
        })
    });

    //test
    // $('#test').click(function(){
    //   var v = $('input[name="month_year"]').val();
    //   var va = v.split('-');
    //   var month = va[1];
    //   var year = va[0];
    //   var project = $('select[name="project"]').val(); 
    //   console.log(typeof month);
    //   console.log(typeof project);
    //   console.log( project);
    // });
</script>

@endsection


