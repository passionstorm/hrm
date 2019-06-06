<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Registration Page</title>
  <base href="{{asset('')}}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>
    @include('messages.errors')
    <form action="admin/register" method="post">
      @csrf
      <div class="form-group has-feedback">
        <label>Role</label>
        <select class="form-control" name="role">
          <option value="0">Normal</option>
          <option value="1">Admin</option>
        </select>
      </div>
      <div class="form-group has-feedback">
        <label>Full name</label>
        <input type="text" class="form-control" placeholder="Full name" name="name">
      </div>
      <div class="form-group has-feedback">
        <label>User name</label>
        <input type="text" class="form-control" placeholder="User name" name="username">
      </div>
      <div class="form-group has-feedback">
        <label>Email</label>
        <input type="email" class="form-control" placeholder="Email" name="email">
      </div>
      <div class="form-group has-feedback">
        <label>Password</label>
        <input type="password" class="form-control" placeholder="Password" name="password">
      </div>
      <div class="form-group has-feedback">
        <label>Retype password</label>
        <input type="password" class="form-control" placeholder="Retype password" name="rpassword">
      </div>
      <div class="form-group has-feedback">
        <label>Organization</label>
        <input type="text" class="form-control" placeholder="Organization" name="organization">
      </div>
      <div class="form-group has-feedback">
        <label>Salary</label>
        <input type="number" class="form-control" placeholder="Salary" name="salary" min="0">
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
