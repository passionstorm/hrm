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
    .input-group-addon {
        width: 0% !important;
    }
    .is_deleted {
        display: none;
    }
    .pd-r-15{padding-right: 15px}
    .callout.callout-info{
        background-color: white !important;
        color: black !important;
    }
    .flex-o1{
        display: flex;
        justify-content: space-between
    }
    .callout a {
        color: rgb(60, 141, 188);
        text-decoration: none;
    }
    .callout a:hover {
        color: rgb(60, 141, 188);
        text-decoration: none;
    }
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
    }
    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    .mb-o1{
        margin-bottom: 10px;
    }
    .w-48p{
        width: 49%;
    }
    .mr-tb-15{
        margin-bottom: 15px;
        margin-top: 15px;
    }
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    {{-- <button id="test">test</button> --}}
    @include('messages.success')
    <?php
        $OnlyAdmin = '';
        if (Auth::user()->role != Constants::ROLE_ADMIN) {
            $OnlyAdmin = 'd-n';
        }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header flex-o1">
        <h1>
            List of projects
        </h1>
        <a href="projects/edit" class="btn btn-primary">Add project</a>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-body">
                    <input type="text" class="form-control mb-o1" id="search" onkeyup="search()" placeholder="Search for names..">
                    <div class="flex-o1">
                        <div class="w-48p">
                            <select name="sort_by" class="form-control" required>
                                <option value="0" disabled hidden selected>Sort by...</option>
                                <option value="1" >Deadline</option>
                                <option value="2" >Budget</option>
                                <option value="3" >Participants</option>
                            </select>                            
                        </div>
                        <div class="w-48p">
                            <select name="sort_direction" class="form-control" required>
                                <option value="0" selected>Decrease</option>
                                <option value="1" >Ascending</option>
                            </select>                            
                        </div>
                    </div>
                    <label for="osau" class="mr-tb-15">
                        <div class="icheckbox_flat-green">
                            <input type="checkbox" id="osau" checked class="hiddent">Show only available projects
                        </div>
                    </label>
                    @foreach($projects as $p)
                        <?php
                            $participants_c = 0;
                            if( $p->participants ){
                                $participants_c = count( explode(',',$p->participants) );
                            }
                            $p->deadline = date('Y-m-d', strtotime($p->deadline));
                        ?>
                        <div class="callout callout-info card @if($p->is_deleted == Constants::IS_DELETED){{'is_deleted'}}@endif" style="margin-bottom: 20px;">
                            <div class="flex-o1">
                                <div style="display: flex; align-items: baseline;">
                                    <h4>{{$p->name}}</h4>
                                    @if($p->is_deleted == Constants::IS_DELETED)
                                        <span class="label label-danger" style="padding: 6px 6px 4px 6px; margin-left: 5px">Deleted</span>
                                    @endif
                                </div>
                                <div class="flex-o1">
                                    <a href="projects/{{$p->id}}/participants/add" class='{{$OnlyAdmin}}'>
                                        <i class="fa fa-user-plus"></i>
                                    </a>
                                    <a href="projects/edit/{{$p->id}}" class='{{$OnlyAdmin}}' style="margin-left:20px">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            <p>Deadline: <span class="deadline">{{$p->deadline}}</span></p>
                            <p>Budget: <b>$</b><span class="budget">{{$p->budget}}</span></p>
                            <p>Participants: <span class="participants">{{$participants_c}}</span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- page script -->
<script>
    $(document).ready(function () {
        //toggle display deleted projects
        $('#osau').change(function(){
            search();
        });
        //end-toggle display deleted projects

        //sort
        $('select').change(function(){
            sort();
        });
        //end-sort

        $('#test').click(function(){

        })
    });

    //support function
    function display(elm){
        if( !elm.hasClass('is_deleted') ){
            return;
        }
        if ( !$('#osau').is(':checked') ) {
            $('.is_deleted').css('display', 'block');
        } else {
            $('.is_deleted').css('display', 'none');
        }
    }
    //end-support function

    //filter by text
    function search(){
        var search = $('#search').val().toUpperCase();
        $('.card').each(function(index){
            if( $(this).find('h4').text().toUpperCase().indexOf(search) != -1 ){
                $( this ).css('display', 'block');
                display( $(this) );
            }else{
                $( this ).css('display', 'none');
            }
        });
    }
    //end-filter by text

    //sort
    function sort(){
        var list = document.getElementsByClassName('card');
            var is_continue = true;
            var is_switch;
            var sort_by = $('select[name="sort_by"]').val();
            var sort_d = $('select[name="sort_direction"]').val();
            while(is_continue){
                is_continue = false;
                for(var i = 0; i<list.length-1; i++){
                    is_switch = false;
                    if( !sort_by ){
                        break;
                    }else if( sort_by == 1 ){
                        var before = Date.parse( list[i].getElementsByClassName('deadline')[0].textContent );
                        var after = Date.parse( list[i+1].getElementsByClassName('deadline')[0].textContent );
                    }else if( sort_by == 2 ){
                        var before = parseFloat( list[i].getElementsByClassName('budget')[0].textContent );
                        var after = parseFloat( list[i+1].getElementsByClassName('budget')[0].textContent ); 
                    }else if( sort_by == 3 ){
                        var before = parseInt( list[i].getElementsByClassName('participants')[0].textContent);
                        var after = parseInt( list[i+1].getElementsByClassName('participants')[0].textContent); 
                    }

                    if( sort_d == 0 && before < after || sort_d == 1 && before > after ){
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