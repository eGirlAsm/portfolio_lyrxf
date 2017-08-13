<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<title>洛阳RXF办公管理系统</title>
<?=linkcss('public/css/reset.css')?>
<?=linkcss('public/css/layout.css')?>
<style>
a{color: #999;}
a:hover{color: #45B6F7;text-decoration:underline;}
.container { margin: 100px auto; width: 1000px; }
.bd { border: 3px solid #dedede; padding: 10px; }
td { padding: 10px; }
input[type=text],input[type=password]{width:200px;}
.submit_btn {background:#257;color:white;}
.submit_btn:hover{background: none repeat scroll 0% 0% #357EBD;
color: #FFF;}
.login_wrap{margin:40px auto;width:300px;border: 1px solid #dedede;}
h2{ background:#257;color:white;text-align:center;font-size:13px;padding:10px 0px;}
</style>
</head>
<body>
<div id="login" class="container bd">
<div class="login_wrap">
<h2>欢迎您使用洛阳RXF办公管理系统</h2>
<form action="login" method="post">
  <table>
    <tr>
      <td>账号:</td>
      <td><input name="u" class="text" type="text" /></td>
    </tr>
    <tr>
      <td>密码:</td>
      <td><input name="p" class="text" type="password" /></td>
    </tr>
    <tr>
      <td></td>
      <td align="right"><input type="submit" class="btn submit_btn" value="登录" /></td>
    </tr>
  </table>
  </form>
  </div>
  <p align="center"><a href="http://www.lyhcwl.com/">洛阳慧创网络</a>版权所有&copy; 2015</p>
</div>
</body>
</html>