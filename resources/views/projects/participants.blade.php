@extends('layout.index')

@section('css')
<style>
    .mb-o1{
        margin-bottom: 10px;
    }
    .mt-o1{
        margin-top: 10px;
    }
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

    .input-group-addon {
        width: 0% !important;
    }

    .pd-r-15 {
        padding-right: 15px
    }
    [id*="remove"]{
        display: none;
    }
    .yes{
        background-color: #85ed7b;
    }
    .hidd{
        display: none;
    }
    .oshow{
        display: inline-block;
    }
    .d-flex-o1{
        display: grid;
    }
    .opt1{
        align-items: baseline;
    }
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('messages.success')
    {{-- <button id="test">test</button> --}}
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <a href="projects/list" style="text-decoration: underline; padding-bottom: 15px; display:block"><i class="fa fa-mail-reply"></i>Back to list</a>
        <h1>
            Add participants for project: {{$project_name}}
        </h1>
        <div class="row opt1">
            <div class="col-xs-12 col-md-6">
                <input type="text" class="form-control mb-o1 mt-o1" id="search" onkeyup="search()" placeholder="Search for names..">
            </div>
            <div class="col-xs-12 col-md-6">
                <select name="sort_by" class="form-control mb-o1 mt-o1" required>
                    <option value="0" selected>Added</option>
                    <option value="1" >Free</option>
                </select>   
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <?php
                                        $active = '';
                                        $hidd = '';
                                        $oshow = '';
                                        foreach($project_participants as $p){
                                            if($u->id == $p){
                                                $active = 'yes';
                                                $hidd = 'hidd';
                                                $oshow = 'oshow';
                                            }
                                        }
                                    ?>
                                    <td width="10%" class="td{{$u->id}} {{$active}}">{{$u->id}}</td>
                                    <td class="name td{{$u->id}} {{$active}}">{{$u->name}}</td>
                                    <td width="10%"><button class="btn btn-primary {{$hidd}}" id="add{{$u->id}}" name="{{$u->id}}"><i class="fa fa-user-plus"></i></button></td>
                                    <td width="10%"><button class="btn btn-danger {{$oshow}}" id="remove{{$u->id}}" name="{{$u->id}}"><i class="fa fa-user-times"></i></button></td>
                                </tr>
                                @endforeach
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
        sort();
        $(document).on('click', '[id*="add"]', function(){
            $addValue = $(this).attr('name');
            $p_id = <?php echo $project_id; ?>;
            //AJAX
            $.ajax({
                url:'projects/participants/add/ajax',
                method:'get',
                data: {
                    'addValue': $addValue,
                    'p_id': $p_id
                },
                dataType: 'json',
                success: function(data){
                    var added_id = data.added_id;
                    $('.td'+added_id).css('background-color', '#85ed7b');
                    $('#add'+added_id).css('display', 'none');
                    $('#remove'+added_id).css('display', 'inline-block');
                }
            });
            //end-AJAX
        });
        $(document).on('click', '[id*="remove"]', function(){
            $removeValue = $(this).attr('name');
            $p_id = <?php echo $project_id; ?>;
            //AJAX
            $.ajax({
                url:'projects/participants/remove/ajax',
                method:'get',
                data: {
                    'removeValue': $removeValue,
                    'p_id': $p_id
                },
                dataType: 'json',
                success: function(data){
                    var removed_id = data.removed_id;
                    $('.td'+removed_id).css('background-color', '#f2d55e');
                    $('#add'+removed_id).css('display', 'inline-block');
                    $('#remove'+removed_id).css('display', 'none');
                }
            });
            //end-AJAX
        });
        //sort
        $('select').change(function(){
            sort(); 
        });
        //end-sort

        $('#test').click(function(){
            var list = document.getElementsByTagName('TABLE')[0].getElementsByTagName('TR');
            var is_continue = true;
            var is_switch;
            var sort_by = $('select[name="sort_by"]').val();
            while(is_continue){
                is_continue = false;
                for(var i = 0; i<list.length-1; i++){
                    is_switch = false;
                    var before = list[i].getElementsByClassName('yes').length;
                    var after = list[i+1].getElementsByClassName('yes').length;
                    if( sort_by == 0 && before < after || sort_by == 1 && before > after ){
                        is_switch = true;
                        break;
                    }
                }
                if(is_switch){
                    list[i].parentNode.insertBefore(list[i+1], list[i]);
                    is_continue = true;
                }
            }
        })
    });

    //filter by text
    function search(){
        var search = $('#search').val().toUpperCase();
        $('table tr').each(function(index){
            if( $(this).find('td.name').text().toUpperCase().indexOf(search) != -1 ){
                $( this ).css('display', 'table-row');
            }else{
                $( this ).css('display', 'none');
            }
        });
    }
    //end-filter by text

    //sort
    function sort(){
        var list = document.getElementsByTagName('TABLE')[0].getElementsByTagName('TR');
        var is_continue = true;
        var is_switch;
        var sort_by = $('select[name="sort_by"]').val();
        while(is_continue){
            is_continue = false;
            for(var i = 0; i<list.length-1; i++){
                is_switch = false;
                var before = list[i].getElementsByClassName('yes').length;
                var after = list[i+1].getElementsByClassName('yes').length;
                if( sort_by == 0 && before < after || sort_by == 1 && before > after ){
                    is_switch = true;
                    break;
                }
            }
            if(is_switch){
                list[i].parentNode.insertBefore(list[i+1], list[i]);
                is_continue = true;
            }
        }
    }
    //end-sort

</script>

@endsection