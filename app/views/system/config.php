<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
  <div class="row">
    <div class="span24">
      <h2>系统设置</h2>
      <hr>
      <!--<?php foreach($param_data as $param); ?>
      <form id="J_Form" class="form-horizontal" method="post" action="#">
      <div class="row">
      <div class="span13">
        <h3>系统信息：</h3>
       <label>系统日期：</label><input name="system_date" type="text" value="<?=$param[system_date]?>" class="calendar" />
</div>
</div>    <div class="row form-actions actions-bar">
        <div class="span13 offset3 ">
          <button type="submit" class="button button-primary">保存</button>
          <button type="reset" class="button">重置</button>
        </div>
       </div>
      </form>-->
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="span24">
      <h2>调试工具</h2>
      <hr>
      <a id="btnShow1" class="button button-info">重置还款状态</a> <a id="btnShow2" class="button button-info">重置刷卡状态</a> </div>
  </div>
  <hr>
  <div class="row">
    <div class="span24">
      <h2>清理工具</h2>
      <hr>
      <a id="btnShow3" class="button button-warning">清理还款记录</a> <a id="btnShow4" class="button button-warning">清理刷卡记录</a> </div>
  </div>  <hr>
    <div class="row">
    <div class="span24">
      <h2>计算工具</h2>
      <hr>
      <!--<a id="btnShow5" class="button button-inverse">计算单期金额</a> -->
  </div>
</div>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<script type="text/javascript">
	BUI.use('bui/overlay',function(overlay){
	function show1 () {
		BUI.Message.Alert('重置还款状态成功!',function(){
			//alert('确认');
			},'success');
		}
		 
		//show();
		$('#btnShow1').on('click',function () {
			
		 $.post("../data/reset/",{code:1},function(result){
			show1();
		 });		
			
			
		});
		
		
	function show2 () {
		BUI.Message.Alert('重置刷卡状态成功!',function(){
			//alert('确认');
			},'success');
		}
		 
		//show();
		$('#btnShow2').on('click',function () {
			$.post("../data/reset/",{code:2},function(result){
				show2();
			});
		});		
		
	function show3 () {
		BUI.Message.Alert('清理还款记录成功!',function(){
			//alert('确认');
			},'success');
		}
		 
		//show();
		$('#btnShow3').on('click',function () {
			$.post("../data/clear/repay",{code:3},function(result){
				show3();
			});
		});	
	
	function show4 () {
		BUI.Message.Alert('清理刷卡记录成功!',function(){
			//alert('确认');
			},'success');
		}
		 
		//show();
		$('#btnShow4').on('click',function () {
			$.post("../data/clear/swipe",{code:4},function(result){
				show3();
			});
		});				
	});
	
	
</script>
 <script type="text/javascript">
BUI.use('bui/calendar',function(Calendar){
var datepicker = new Calendar.DatePicker({
trigger:'.calendar',
autoRender : true
});
});
</script>
</body>
</html>