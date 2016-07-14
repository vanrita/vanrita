<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1>你好，世界！</h1>
    <!-- Button trigger modal -->
<a  class="" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <form  >
      <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="modal-body">
              <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input id="username" type="text" class="form-control" name="name" placeholder="username">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">密码</label>
            <input id="password" type="password" class="form-control" name="password" placeholder="password">
          </div>
          </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button id="sign" type="button" class="btn btn-primary">登录</button>
          </div>
      </form>
    </div>
  </div>
</div>
<input type="text" id="datetimepicker2"/><br><br>
    
    <table id="example" class="display" width="100%"></table>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="js/test.js"></script>
    <script src="js/jquery.datetimepicker.full.js"></script>
    <script>
      $('#datetimepicker2').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y/m/d',
        maxDate:'+1970/01/01' // and tommorow is maximum date calendar
      });

    </script>
  </body>
</html>