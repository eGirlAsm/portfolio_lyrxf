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
        <div class="control-group span8">
          <label class="control-label"><s>*</s>所属银行：</label>
          <div class="controls">
            <select  name="banktype" data-rules="{required:true}">
              <option value="">-请选择银行-</option>
              <?php foreach ($bank_data as $bank){ ?>
              <option value="<?=$bank['name']?>"><?=$bank['name']?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">        <div class="control-group span15 ">
          <label class="control-label"><s>*</s>激活日期：</label>
          <div  class="controls bui-form-group"  >
            <input name="activedate"  class="input-small date4"    type="text">
          </div>
        </div>
        <div class="control-group span15 ">
          <label class="control-label"><s>*</s>账单日期：</label>
          <div  class="controls bui-form-group"  >
            <input name="statedate"  class="input-small date"    type="text">
          </div>
        </div>
        <div class="control-group span15 ">
          <label class="control-label"><s>*</s>到期还款日：</label>
          <div  class="controls bui-form-group"  >
            <input name="repaydate"  class="input-small date"     type="text">
          </div>
        </div>
        <div class="control-group span15 ">
          <label class="control-label"><s>*</s>总额度：</label>
          <div  class="controls bui-form-group"  >
            <input name="limit"  data-rules="{required:true}" class="input-normal control-text"    type="text">
          </div>
        </div>
        <div class="control-group span15 ">
          <label class="control-label"><s>*</s>人民币账单：</label>
          <div  class="controls bui-form-group"  >
            <input name="bill"  data-rules="{required:true}" class="input-normal control-text"    type="text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label">是否分期：</label>
          <div class="controls">
            <select  name="installment" >
              <option value="0">-请选择分期-</option>
              <option value="3">3期</option>
              <option value="6">6期</option>
              <option value="9">9期</option>
              <option value="10">10期</option>
              <option value="12">12期</option>
              <option value="18">18期</option>
              <option value="24">24期</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span15 ">
          <label class="control-label">分期账单：</label>
          <div class="controls bui-form-group"  >
            <input name="installment_bill"   class="input-normal control-text"    type="text">
          </div>
        </div>
               <!-- <div class="control-group span15 ">
          <label class="control-label">每期账单：</label>
          <div  class="controls bui-form-group"  >
            <input name="each_bill" readonly  class="input-normal control-text"    type="text">
          </div><label class="control-label"><s>(自动计算)</s></label>
        </div>-->
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
 
  BUI.use('bui/calendar',function(Calendar){
		var datepicker = new Calendar.DatePicker({
		trigger:'.date4',
		autoRender : true
		});
 });
</script>

<body>
</html>
