<!DOCTYPE HTML>
<html>
<head>
<title>资源文件结构</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div class="container">
  <div class="row">
    <div class="doc-content">
      <ul class="breadcrumb">
        <li><a  class="page-action" data-id="index" data-mid="menu" href="#">后台首页</a> <span class="divider">/</span></li>
        <li><a  class="page-action" data-id="swipe_table" data-mid="swipe" href="#">刷卡管理</a> <span class="divider">/</span></li>
        <li class="active">添加刷卡记录</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <form id="J_Form" class="form-horizontal span24">
      <div class="row">
        <h3>添加刷卡记录</h3>
        <hr>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>卡号：</label>
          <div class="controls">
            <input name="cardid" type="text" data-rules="{number:true,maxlength:17}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>姓名：</label>
          <div class="controls">
            <input name="realname" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span15">
          <label class="control-label">POS机：</label>
          <div class="controls bui-form-group-select"  data-type="test">
            <select  class="input-normal" name="province" value="山东省">
              <option>请选择POS机</option>
            </select>
            <select class="input-normal"  name="city" value="淄博市">
              <option>POS机费率</option>
            </select>
            <select class="input-normal"  name="county" value="淄川区">
              <option>POS机姓名</option>
            </select>
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

<script type="text/javascript">



  BUI.use('bui/form',function (Form) {
	  

    var data = [{"id" : "1","text":"#001","children":[
      {"id":"11","text":"0.8%","leaf":false},
      {"id":"12","text":"1.2%","leaf":false,"children":[
        {"id":"121","text":"高青","leaf":true}
      ]}
    ]}];
    BUI.Form.Group.Select.addType('test',{
      data : data
    });
		  
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
