<form action="register" method="post" enctype="multipart/form-data">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group">
      <label>Role</label>
      <select class="form-control" name="role">
        <option value="0">Member</option>
        <option value="2">Staff</option>
        <option value="1">Admin</option>
      </select>
    </div>
    <div class="form-group">
      <label>Full name</label>
      <input type="text" class="form-control" placeholder="Full name" name="name">
    </div>
    <div class="form-group">
      <label>User name</label>
      <input type="text" class="form-control" placeholder="User name" name="username">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" placeholder="Email" name="email">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" placeholder="Password" name="password">
    </div>
    <div class="form-group">
      <label>Retype password</label>
      <input type="password" class="form-control" placeholder="Retype password" name="rpassword">
    </div>
    <div class="form-group">
      <label>Organization</label>
      <input type="text" class="form-control" placeholder="Organization" name="organization">
    </div>
    <div class="form-group">
      <label>Salary</label>
      <input type="number" class="form-control" placeholder="Salary" name="salary" min="0">
    </div>
    <div class="form-group">
      <label>Avatar</label>
      <input type="file" class="form-control" name="avatar">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Add User</button>
    </div>    
  </div>
</form>