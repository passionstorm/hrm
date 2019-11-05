  <?php 
    use App\Constants;$OnlyAdmin = '';
    if(Auth::check() && Auth::user()->role != Constants::ROLE_ADMIN){
      $OnlyAdmin = 'disabled';
    }
    $m_user = (object) array(
      'id' => '',
      'role' => '',
      'name' => '',
      'username' => '',
      'email' => '',
      'salary' => '',
      'company_name' => '',
      'avatar' => '',
    );
    $var = '';
    $display = '';
    $mg1 = '';
    $avatar = '';
    $require = 'required';
    if(isset($user)){
      $m_user = $user;
      $var = '/'.$user->id;
      $display = 'd-n';
      $mg1 = 'mg-t-10';
      $avatar = 'upload/avatar/'.$m_user->avatar;
      $require = '';
    }
    $rv_hidden = '';
    $rv_disable = '';
    $rv_only_disable = 'disabled';
    $rv_only_hidden = 'd-n';
    if(isset($registerView)){
      $rv_hidden = "d-n";
      $rv_disable = "disabled"; 
      $rv_require = 'required';
      $rv_only_disable = '';
      $rv_only_hidden = '';
    }
  ?>
<!-- form start -->
<form role="form" action="users/edit{{$var}}" method="post" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="firstRegister" value="{{isset($registerView)}}">
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group {{$rv_hidden}}">
      <label>Role</label>
      <select class="form-control" name="role" {{$OnlyAdmin}}>
        <option value="{{Constants::ROLE_MEMBER}}"
          @if($m_user->role == Constants::ROLE_MEMBER)
            {{'selected'}} 
          @endif
        >Member</option>
        <option value="{{Constants::ROLE_STAFF}}"
          @if($m_user->role == Constants::ROLE_STAFF)
            {{'selected'}} 
          @endif
        >Staff</option>
        <option value="{{Constants::ROLE_ADMIN}}"
          @if($m_user->role == Constants::ROLE_ADMIN || isset($registerView))
            {{'selected'}} 
          @endif
        >Admin</option>`
      </select>
    </div>
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" name="name" value="{{$m_user->name}}" required>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" name="username" required value="{{$m_user->username}}" 
      @if($m_user->username)
        {{'disabled'}}
      @endif
      >
    </div>
    <div class="form-group cont {{$display}}">
      <label>Password</label>
      <input type="password" class="form-control" name="password" {{$require}}>
    </div>
    <div class="form-group cont {{$display}}">
      <label>Retype password</label>
      <input type="password" class="form-control" name="retype_password" {{$require}}>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" name="email" value="{{$m_user->email}}" required>
    </div>
    <div class="form-group {{$rv_only_hidden}}">
      <label>Organization</label>
      <input type="text" class="form-control" name="company_name" {{$rv_only_disable}} required>
    </div>
    <div class="form-group {{$rv_hidden}}">
      <label>Salary</label>
      <input type="number" class="form-control" placeholder="Salary" name="salary" min="0"  value="{{$m_user->salary}}" {{$OnlyAdmin}} {{$rv_disable}}>
    </div>
    <div class="form-group {{$rv_hidden}}">
      <label>Avatar</label>
      @if($avatar)<img width="100" src="{{$avatar}}" alt="avatar" style="display: block;">@endif
      <input type="file" class="form-control {{$mg1}}" name="avatar" {{$rv_disable}}>
    </div>

    <div class="pull-right">
        @if(isset($user))
        <a href="users/delete/{{$user->id}}" class="btn btn-danger">Delete user</a>
        @endif
      <button type="submit" class="btn btn-primary" style="width: 10em"> Submit</button>
    </div>
  </div>
</form>


