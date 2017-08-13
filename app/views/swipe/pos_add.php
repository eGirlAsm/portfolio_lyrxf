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
      <h3>POS机管理</h3>
      <hr>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>POS机编号：</label>
          <div class="controls">
            <input name="cardid" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>POS机费率：</label>
          <div class="controls">
            <input name="rate" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>POS机商户名称：</label>
          <div class="controls">
            <input name="shopname" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>机主姓名：</label>
          <div class="controls">
            <input name="realname" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>机主身份证：</label>
          <div class="controls">
            <input name="passportid" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>POS机所属银行：</label>
          <div class="controls">
            <input name="bank" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>POS机卡号：</label>
          <div class="controls">
            <input name="cardnumber" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div> 
               <div class="control-group span8">
          <label class="control-label"><s>*</s>机主手机号码：</label>
          <div class="controls">
            <input name="phone" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>  
              
      </div>
      <div class="row">
        <div class="control-group span15">
          <label class="control-label">备注：</label>
          <div class="controls control-row4">
            <textarea name="memo" class="input-large" data-tip="{text:'请填写备注信息！'}" type="text"></textarea>
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
		dateMask : 'dd',
		autoRender : true
		});
 });
</script>

<body>
</html>
