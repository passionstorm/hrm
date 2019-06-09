<!-- form start -->
<form role="form" action="user/edit/{{$user->id}}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group">
      <label>Role</label>
      <select class="form-control" name="role">
        <option value="0"
          @if($user->role == 0)
            {{'selected'}} 
          @endif
        >Member</option>
        <option value="2"
          @if($user->role == 2)
            {{'selected'}} 
          @endif
        >Staff</option>
        <option value="1"
          @if($user->role == 1)
            {{'selected'}} 
          @endif
        >Admin</option>
      </select>
    </div>
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" name="name" value="{{$user->name}}">
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" name="username" value="{{$user->username}}">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" name="email" value="{{$user->email}}">
    </div>
    <div class="form-group">
      <label>Organization</label>
      <input type="text" class="form-control" name="organization" value="{{$user->organization}}">
    </div>
    <div class="form-group">
      <label>Salary</label>
      <input type="number" class="form-control" placeholder="Salary" name="salary" min="0"  value="{{$user->salary}}">
    </div>
    <div class="form-group">
      <label>Avatar</label>
      <p><img width="100" src="upload/avatar/{{$user->avatar}}" alt=""></p>
      <input type="file" class="form-control" name="avatar">
    </div>
    <div class="row">
      <div class="col-lg-3">
        <div class="input-group">
          <span class="input-group-addon iga-1"><input type="checkbox" name="ChangePassword" id="ChangePassword"></span>
          <span class="input-group-addon"><b>Change password</b></span>
        </div>
        <!-- /input-group -->
      </div>
    </div>
    <div class="form-group cont" hidden>
      <label>Password</label>
      <input type="password" class="form-control" placeholder="Password" name="password">
    </div>
    <div class="form-group cont" hidden>
      <label>Retype password</label>
      <input type="password" class="form-control" placeholder="Retype password" name="rpassword">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary" style="width: 10em">Edit</button>
    </div>
  </div>
</form>

