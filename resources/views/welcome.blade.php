<?php
  $countries = DB::table('countries')->get();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <style>
      .f-700{
        font-weight: 700;
      }
      .s-1{
        background-color:  #F4E2DE;
      }
      .s-2{
        background-color:  #ffd257;
      }
      .s-3{
        background-color: #ededed;
        padding: 20px 0 30px 0;
      }
      .d-f-1{
        display: flex;
      }
      .d-f-1-1{
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-basis: 30%;
      }
      .d-f-1-2{
        display: flex;
        align-items: baseline;
        justify-content: center;
        flex-basis: 70%;
      }
      .b-1{
        font-size: 350px;
        font-weight: 700;
        color: black;
      }
      .b-2{
        font-size: 40px;
        color: black;
        font-weight: 700;
      }
      .h1-1{
        font-weight: 700;
      }
      .f-right{
        float: right;
      }
      .mg-r-15{
        margin-right: 15px;
      }
      .mg-b-0{
        margin-bottom: 0;
      }
      .header-1{
        background-color: #b80303;
        color: #fff;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1;
      }
      .a-1, .a-1:hover{
        color: inherit;
        text-decoration: none;
      }
      .a-1:hover{
        color: #ffb3b3;
      }
      .pd-tb-15{
        padding-bottom: 15px;
        padding-top: 15px;
      }
      .pd-b-30{
        padding-bottom: 30px
      }
      .bd-r-1{
        border-right: 1px solid rgb(51, 51, 51);
      }
      .mg-t-20{
        margin-top: 20px;
      }
      .n-pd{
        padding-left: 0;
        padding-right: 0;
      }
      .region{
        font-weight: 700;
      }
      .language{
        opacity: 0.8;
      }
      .dropdown-menu-1{
        margin: 0;
        padding: 0 !important;
        border: none;
        background-color: rgb(77, 77, 77);
        font-size: 13px;
      }
      .dropdown-menu-1 img{
        margin: 0px 8px;
        width: 20px;
        height: 15px;
      }
      .dropdown-menu-1 a{
        display: block;
        padding: 4px 8px;
      }
      .dropdown-menu-1 a, .dropdown-menu-1 a:hover{
        color: #fff;
        text-decoration: none;
      }
      .dropdown-menu-1 a:hover{
        background-color: rgb(38, 38, 38);
      }
      .dropdown-menu-1  div:first-child a:first-child{
        background-color: rgb(38, 38, 38);
      }
      .dropdown-toggle:hover{
        cursor: pointer;
        background-color: #f54242
      }
      .c1{
        display: inline-block;
        padding: 15px 10px;
      }
      .image-holder{
        width: 500px;
        height: 300px;
        background-color: #657569;
      }
      .footer{
        background-color: #474747;
        color: white;
        padding: 20px 0;
      }
      .footer i{
        color: white
      }
      .perfect-center{
        display: flex;
        justify-content: center;
        align-content: center;
      }
      .equal-line{
        display: flex;
        align-items: baseline; 
      }
    </style>
  </head>

  <body>
    <header class="header-1">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="col-xs-4 dropdown n-pd">
              <span class="dropdown-toggle c1" data-toggle="dropdown">
                <img src="img/flag-4x3/{{$countries[0]->code}}.svg" width="20" height="15">
                {{$countries[0]->name}}
                <span class="language">({{$countries[0]->language}})</span>
              </span>
                <?php $i=0 ?>
                <div class="dropdown-menu dropdown-menu-1">
                  @for (;$i < count($countries); $i++)
                    @if ($i % 9 == 0)
                      <div class="n-pd">
                      <?php $y = $i + 8 ?>
                    @endif
                    <a href="#" class="">
                      <img src="img/flag-4x3/{{$countries[$i]->code}}.svg">
                      <span class="region">{{$countries[$i]->name}}</span>
                      <span class="language">{{$countries[$i]->language}}</span>
                    </a>
                    @if ($i == $y)
                      </div>
                    @endif
                  @endfor
                </div>
              </div>
            </div>
            <div class="col-xs-8">
              <div class="f-right pd-tb-15">
                <a href="login" class="a-1 mg-r-15">Login</a>
                <a href="register" class="a-1">Register</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <main>
      <div class="s-1">
        <div class="container">
          <div class="d-f-1">
            <div class="d-f-1-1">
              <h1 class="h1-1">Software Company Manager</h1>
              <h3 class="redesign-zero-cost ">FREE, DYNAMID and SMART!</h2>
            </div>
            <div class="d-f-1-2">
              <div class="b-1">0</div>
              <div class="b-2">usd/month</div>
            </div>
          </div>
        </div>
      </div>
      <div class="s-2">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 pd-b-30">
              <h2 class="text-center f-700">Why choose {{Constants::WEB_NAME}}?</h2>
              <p class="text-center">Whether you are a unprofessional or professional company, our  website support you to manager human resource.</p>
              <div class="row box-holder mg-t-20">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-push-6 col-sm-push-6 box">
                  <div class="image-holder"></div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-pull-6 col-sm-pull-6 box">
                  <div class="text">
                    <div class="text-box">
                      <div class="content">
                        <h3 class="f-700">Instant Account Activation</h3>
                        <p>All {{Constants::WEB_NAME}} accounts are activated instantly. Start working on your project as soon as you register! Whether it’s a small or big company, all the possibilities are within your fingertips..</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row box-holder mg-t-20">
                <div class="col-md-6 col-sm-6 col-xs-12 box">
                  <div class="image-holder"></div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 box">
                  <div class="text right">
                    <div class="text-box">
                      <div class="content">
                        <h3 class="f-700">Simple & Easy to use</h3>
                        <p>Users only need 15 minutes to get started to manage company. The simple, friendly, and smart interface helps you deploy sales management easily and quickly.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row box-holder mg-t-20">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-push-6 col-sm-push-6 box">
                  <div class="image-holder"></div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-pull-6 col-sm-pull-6 box">
                  <div class="text">
                    <div class="text-box">
                      <div class="content">
                        <h3 class="f-700">There are many features</h3>
                        <p>With {{Constants::WEB_NAME}}, you can manage human resources, manage projects, manage time... And more</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row box-holder mg-t-20">
                <div class="col-md-6 col-sm-6 col-xs-12 box">
                  <div class="image-holder"></div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 box">
                  <div class="text right">
                    <div class="text-box">
                      <div class="content">
                        <h3 class="f-700">Constantly updated</h3>
                        <p>We research and listen to user feedback to improve the product</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="s-3">
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <h2 class="text-center f-700">How to start?</h2>
              <p class="text-center">Work manager takes patience, dedication and time. But on {{Constants::WEB_NAME}}, it’s easy, fast & fun! Starting your online adventure is a simple four-step process.</p>
              <div class="col-xs-12">
                <ul style="list-style-type:none;">
                  <li>
                    <h3 class="f-700">Register an admin account</h3>
                    <p>Fisrt, complete the registering to become a member of {{Constants::WEB_NAME}}. Then, set up company information from within the control panel. The company page will immediately be activated, so you can start managing your company</p>
                  </li>
                  <li>
                    <h3 class="f-700">Add employee's accounts</h3>
                    <p>Add eemployee's accounts. Then, send these accounts to your employees so they can use them</p>
                  </li>
                  <li>
                    <h3 class="f-700">Start working</h3>
                    <p>Now, you and your employees can use all of features</p>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer class="footer">
      <div class="container">
        <div class="row equal-line">
          <div class="col-xs-10">
            <p class="mg-b-0">Copyright &copy; 2014-2019 <b>{{Constants::WEB_NAME}}</b>. All rights reserved.<b><a href="/privacy" style="color: #fff">Privacy Policy</a></b></p>
          </div>
          <div class="col-xs-2">
              <a href="https://twitter.com" class="btn btn-twitter btn-social">
                <i class="fa fa-twitter"></i>
              </a>
              <a href="https://www.facebook.com" class="btn btn-facebook btn-social">
                <i class="fa fa-facebook"></i>
              </a>
              <a href="https://github.com/passionstorm/hrm" class="btn btn-github btn-social">
                <i class="fa fa-github"></i>
              </a>
          </div>
        </div>
      </div>
    </footer>

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function(){
        $('a span.language').each(function(){
          $(this).text('| '+$(this).text());
        });
      });
    </script>
  </body>
</html>