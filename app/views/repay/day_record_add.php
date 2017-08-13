<!DOCTYPE HTML>
<html>
<head>
<title>资源文件结构</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<script src="../public/select/jquery-1.11.1.min.js"></script>
<script src="../public/select/jquery-ui.min.js"></script>
<script src="../public/select/jquery.select-to-autocomplete.js"></script>
<script>
	  (function($){
	    $(function(){
	
	      $('select').selectToAutocomplete();
	      $(document).keydown(function(e) {
              var ev = document.all ? window.event : e;
				if(ev.keyCode==13) {
			        var index = $("select").get(0).selectedIndex;
					var bank = $($("select").get(0).options[index]).attr("data-bank");
       				var name = $($("select").get(0).options[index]).attr("data-name");
					 $('input[name=banktype]').val(bank);
					 $('input[name=realname]').val(name);
					 $('input[name=payamount]').focus();
				 } 
        });
		
		//$(document).ready(function(e) {
            //$('.date').focus(function(e) {
                //$(this).val('123');
            //});
        //});
	     // $('form').submit(function(){
	        //alert( $(this).serialize() );
	        //return false;
	     // });
	    });
	  })(jQuery);
	</script>
<link rel="stylesheet" href="../public/select/jquery-ui.css">
<style>
.ui-autocomplete { padding: 0; list-style: none; background-color: #fff !important; width: 218px; border: 1px solid #B0BECA; max-height: 350px; overflow-x: hidden; }
.ui-autocomplete .ui-menu-item { border-top: 1px solid #B0BECA; display: block; padding: 4px 6px; color: #353D44; cursor: pointer; }
.ui-autocomplete .ui-menu-item:first-child { border-top: none; }
.ui-autocomplete .ui-menu-item.ui-state-focus { background-color: #D5E5F4 !important; color: #161A1C; }
</style>
</head>
<body>
<div class="container">
 <!-- <div class="row">
    <div class="doc-content">
      <ul class="breadcrumb">
        <li><a  class="page-action" data-id="index" data-mid="menu" href="#">后台首页</a> <span class="divider">/</span></li>
        <li><a  class="page-action" data-id="day_payment_table" data-mid="repay" href="#">还款管理</a> <span class="divider">/</span></li>
        <li class="active">添加还款记录</li>
      </ul>
    </div>
  </div>-->
  <div class="row">
    <form id="J_Form" class="form-horizontal span24" method="post">
      <div class="row">
        <h3>添加卡号信息</h3>
        <hr>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>卡号：</label>
          <div class="controls">
            <select name="cardnumber"  data-rules="{required:true}"  id="country-selector" autofocus autocorrect="off" autocomplete="off" class="input-normal">
              <option value="" selected="selected">请选择卡号</option>
              <?php foreach($card_data as $data){?>
              <option value="<?=$data[cardnumber]?>" data-name="<?=$data[name]?>" data-bank="<?=$data[banktype]?>" data-alternative-spellings="<?=$data[name]?> <?=$data[cardnumber]?>"><?=$data[cardnumber]?></option>
              <?php }?>
            </select>
          </div>
        </div>
        <div class="control-group span8">
          <label class="control-label"><s>*</s>选择发卡银行：</label>
          <div class="controls">
            <input name="banktype" type="text" data-rules="{required:true}" class="input-normal control-text">
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
          <label class="control-label"><s>*</s>还款金额：</label>
          <div class="controls">
            <input name="payamount" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
        <div class="control-group span15 ">
          <label class="control-label">操作人：</label>
          <div id="zhangdanri" class="controls bui-form-group"  >
            <input name="operator" value="<?=Session::get('realname')?>"    readonly class="input-normal control-text"    type="text">
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
    var form = new Form.HForm({
      srcNode : '#J_Form'
    });

    form.render();
  });

 BUI.use('bui/calendar',function(Calendar){
		var datepicker = new Calendar.DatePicker({
		trigger:'.date',
		showTime:true,
		//selectedDate : new Date('2013/07/01'), //不能使用字符串
	
		autoRender : true
		});
 });
</script>

<body>
</html>
