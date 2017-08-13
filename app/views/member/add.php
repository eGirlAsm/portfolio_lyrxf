<!DOCTYPE HTML>
<html>
<head>
<title>资源文件结构</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
<link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
<style type="text/css">
code { padding: 0px 4px; color: #d14; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
</style>
</head>
<body>
<div class="container">
  <div class="row">
    <form id="J_Form" class="form-horizontal span24" method="post">
      <div class="row">
        <h3>添加卡号信息</h3>
        <hr>
        <div class="control-group span16">
          <label class="control-label"><s>*</s>帐号：</label>
          <div class="controls">
            <input name="username" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span16">
          <label class="control-label"><s>*</s>密码：</label>
          <div class="controls">
            <input name="password" type="password" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span16">
          <label class="control-label"><s>*</s>姓名：</label>
          <div class="controls">
            <input name="realname" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span16">
          <label class="control-label"><s>*</s>权限：</label>
          <div class="controls">
             <select name="privilege">
             <?php foreach($privilege as $p){ ?>
              <option value="<?=$p[name]?>"><?=$p[name]?></option>
              <?php }?>

            </select>
          </div>
        </div>
      </div>
      <div class="row form-actions actions-bar">
        <div class="span13 offset3 ">
          <button type="submit" class="button button-primary">保存</button>
          <button type="reset" class="button">重置</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<!-- 仅仅为了显示代码使用，不要在项目中引入使用--> 
<script type="text/javascript" src="../assets/js/prettify.js"></script> 
<script type="text/javascript">
    $(function () {
      prettyPrint();
    });
  </script> 
<script type="text/javascript">
  BUI.use('bui/form',function (Form) {
    var form = new Form.HForm({
      srcNode : '#J_Form'
    });

    form.render();
  });
  
 /*BUI.use('bui/calendar',function(Calendar){
var monthpicker = new BUI.Calendar.datePicker({
render:'#month',
month:1,
visible:true,
align : {
points:['tl','tl']
},
year:2000,
success:function(){
alert(this.get('year') + ' ' + this.get('month'));
}
});
monthpicker.render();
});  */
 BUI.use('bui/calendar',function(Calendar){
		var datepicker = new Calendar.DatePicker({
		trigger:'.date',
		autoRender : true
		});
 });
</script>

<body>
</html>
