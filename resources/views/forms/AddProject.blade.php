<form action="register" method="post">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group">
      <label>Country of customers</label>
      <select class="form-control" name="role">
        <option value="0">VietNam</option>
        <option value="1">Jappan</option>
      </select>
    </div>
    <div class="form-group">
      <label>Name of project</label>
      <input type="text" class="form-control" placeholder="User name" name="username">
    </div>
    <div class="form-group">
      <label>Customer name</label>
      <input type="text" class="form-control" placeholder="Full name" name="name">
    </div>
    <div class="form-group">
      <label>Budget</label>
      <input type="email" class="form-control" placeholder="Email" name="email">
    </div>
    <div class="form-group">
      <label>Deadline</label>
      <input type="password" class="form-control" placeholder="Password" name="password">
    </div>
    <div class="form-group">
      <label>Describe</label>
      <input type="password" class="form-control" placeholder="Retype password" name="rpassword">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Add User</button>
    </div>    
  </div>
</form>