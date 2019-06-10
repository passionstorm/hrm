<form action="ots/add" method="post">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="d-grid">
      
      <div class="d-grid1">

      <div>
        <!-- time Picker -->
        <div class="bootstrap-timepicker">
          <div class="form-group">
            <label>Start time:</label>

            <div class="input-group">
              <input type="text" class="form-control timepicker">

              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
            <!-- /.input group -->
          </div>
          <!-- /.form group -->
        </div>
      </div>

      <div>
        <!-- time Picker -->
        <div class="bootstrap-timepicker">
          <div class="form-group">
            <label>End time:</label>

            <div class="input-group">
              <input type="text" class="form-control timepicker">

              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
            <!-- /.input group -->
          </div>
          <!-- /.form group -->
        </div>
      </div>

      <div>
        <div class="form-group">
          <label>Project</label>
          <select class="form-control" name="c_country">
            @foreach($projects as $p)
              @if($p->is_deleted == 1)
                @continue
              @endif
              <option value="{{$p->id}}">{{$p->name}}</option>
            @endforeach        
          </select>
        </div>
      </div>

      </div>

      <div class="d-flex">
        <button class="btn btn-success" type="button"> <span class="glyphicon glyphicon-plus"></span> </button>
      </div>

    </div>



    <div class="row">
      <div class="col-lg-3">
        <div class="input-group">
          <span class="input-group-addon iga-1"><input type="checkbox" id="osau"></span>
          <span class="input-group-addon"><b>Comment</b></span>
        </div>
      </div>
    </div>
    <div class="form-group d-n">
      <textarea name="describe" id="editor1" name="editor1" rows="10" cols="80"></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Submit</button>
    </div>    
  </div>
</form>