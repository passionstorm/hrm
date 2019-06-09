<form action="projects/add" method="post">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group">
      <label>Country of customers</label>
      <select class="form-control" name="c_country">
        <option value="0">VietNam</option>
        <option value="1">Jappan</option>
      </select>
    </div>
    <div class="form-group">
      <label>Name of project</label>
      <input type="text" class="form-control" name="name">
    </div>
    <div class="form-group">
      <label>Customer name</label>
      <input type="text" class="form-control" name="c_name">
    </div>
    <div class="form-group">
      <label>Budget</label>
      <input type="number" class="form-control" name="budget" min="0">
    </div>
    <!-- Date -->
    <div class="form-group">
      <label>Deadline:</label>
      <div class="input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control pull-right" id="datepicker" name="deadline">
      </div>
    </div>
    
    <div class="form-group">
      <label>Describe</label>
      <textarea name="describe" id="editor1" name="editor1" rows="10" cols="80"></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Add</button>
    </div>    
  </div>
</form>