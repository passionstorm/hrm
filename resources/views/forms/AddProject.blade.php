<form action="register" method="post">
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
      <input type="text" class="form-control" placeholder="User name" name="name">
    </div>
    <div class="form-group">
      <label>Customer name</label>
      <input type="text" class="form-control" placeholder="Full name" name="c_name">
    </div>
    <div class="form-group">
      <label>Budget</label>
      <input type="number" class="form-control" placeholder="budget" name="budget" min="0">
    </div>
    <!-- Date -->
    <div class="form-group">
      <label>Date:</label>
      <div class="input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control pull-right" id="datepicker">
      </div>
    </div>
    
    <div class="form-group">
      <label>Describe</label>
      <textarea class="form-control" name="" id="" cols="30" rows="3"></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Add User</button>
    </div>    
  </div>
</form>