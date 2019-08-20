  <?php 
    use App\Constants;$OnlyAdmin = '';
    if(Auth::user()->role != Constants::ROLE_ADMIN){
      $OnlyAdmin = 'disabled';
    }

    $m_user = (object) array(
      'id' => '',
      'role' => '',
      'name' => '',
      'username' => '',
      'email' => '',
      'salary' => '',
      'organization' => '',
      'avatar' => '',
    );

    $var = '';
    $display = '';

    if(isset($user)){
      $m_user = $user;
      $var = '/'.$user->id;
      $display = 'd-n';
    }


  ?>
<!-- form start -->
<form role="form" action="users/edit{{$var}}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="box-body" style="padding-bottom: 20px">
    <div class="form-group">
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
          @if($m_user->role == Constants::ROLE_ADMIN)
            {{'selected'}} 
          @endif
        >Admin</option>`
      </select>
    </div>
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" name="name" value="{{$m_user->name}}">
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" name="username" value="{{$m_user->username}}" 
      @if($m_user->username)
        {{'disabled'}}
      @endif
      >
    </div>
    <div class="form-group cont {{$display}}">
      <label>Password</label>
      <input type="password" class="form-control" name="password" >
    </div>
    <div class="form-group cont {{$display}}">
      <label>Retype password</label>
      <input type="password" class="form-control" name="retype_password">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" name="email" value="{{$m_user->email}}">
    </div>
    <div class="form-group">
      <label>Organization</label>
      <input type="text" class="form-control" name="organization" value="{{$m_user->organization}}" {{$OnlyAdmin}}>
    </div>
    <div class="form-group">
      <label>Salary</label>
      <input type="number" class="form-control" placeholder="Salary" name="salary" min="0"  value="{{$m_user->salary}}" {{$OnlyAdmin}}>
    </div>
    <div class="form-group">
      <label>Avatar</label>
      <p><img width="100" src="upload/avatar/{{$m_user->avatar}}" alt=""></p>
      <input type="file" class="form-control" name="avatar">
    </div>

    <div class="pull-right">
        @if(isset($user))
        <a href="users/delete/{{$user->id}}" class="btn btn-danger">Delete user</a>
        @endif
      <button type="submit" class="btn btn-primary" style="width: 10em"> Submit</button>
    </div>
  </div>
</form>

