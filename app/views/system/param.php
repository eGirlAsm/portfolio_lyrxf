<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<style>
 .bui-stdmod-body{
overflow-x : hidden;
overflow-y : auto;
padding:10px !important;
}
#btnShow {text-decoration:underline;}
#btnExport {text-decoration:underline;}
#btnClear {text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="span24">
      <h2>参数设置</h2>
      <hr>
      <?php foreach($param as $p) { ?>
      <form id="J_Form" class="form-horizontal" method="post" action="#">
        <h3>参数信息：</h3>
        <div class="control-group">
          <label class="control-label"><s>*</s>当前资金(元)：</label>
          <div class="controls">
            <input type="text"  readonly  class="input-normal" value="<?=$p[funds]?>" />  <input type="text" class="input-normal addfunds" value="" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button button-primary" value="添加资金"  onClick="addfunds();" />&nbsp;&nbsp;&nbsp;&nbsp;<a id="btnShow" href="#">查看记录</a>&nbsp;&nbsp;&nbsp;&nbsp;<a id="btnExport" href="#">导出记录</a>&nbsp;&nbsp;&nbsp;&nbsp;<a id="btnClear" href="#">清空记录</a>
          </div>
        </div>   

        
        
        <h3>百分比: 还款公式 ： 还款金额 × <font color='red'>30%</font> / 13 = 每日还款金额,刷卡公式 ： 刷卡金额 / <font color='red'>600</font> = POS机分配</h3>
  
          
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>10万以下(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent" class="input-normal" value="<?=$p[paypercent]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>10万以下：</label>
              <div class="controls">
                <input type="text" name="posamount" class="input-normal" value="<?=$p[posamount]?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>10万~20万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent10_20" class="input-normal" value="<?=$p[paypercent10_20]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>10万~20万：</label>
              <div class="controls">
                <input type="text" name="posamount10_20" class="input-normal" value="<?=$p[posamount10_20]?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>20万~30万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent20_30" class="input-normal" value="<?=$p[paypercent20_30]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>20万~30万：</label>
              <div class="controls">
                <input type="text" name="posamount20_30" class="input-normal" value="<?=$p[posamount20_30]?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>30万~40万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent30_40" class="input-normal" value="<?=$p[paypercent30_40]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>30万~40万：</label>
              <div class="controls">
                <input type="text" name="posamount30_40" class="input-normal" value="<?=$p[posamount30_40]?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>40万~50万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent40_50" class="input-normal" value="<?=$p[paypercent40_50]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>40万~50万：</label>
              <div class="controls">
                <input type="text" name="posamount40_50" class="input-normal" value="<?=$p[posamount40_50]?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>50万~60万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent50_60" class="input-normal" value="<?=$p[paypercent50_60]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>50万~60万：</label>
              <div class="controls">
                <input type="text" name="posamount50_60" class="input-normal" value="<?=$p[posamount50_60]?>" />
              </div>
            </div>
          </div>        <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>60万~70万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent60_70" class="input-normal" value="<?=$p[paypercent60_70]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>60万~70万：</label>
              <div class="controls">
                <input type="text" name="posamount60_70" class="input-normal" value="<?=$p[posamount60_70]?>" />
              </div>
            </div>
          </div>        <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>70万~80万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent70_80" class="input-normal" value="<?=$p[paypercent70_80]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>70万~80万：</label>
              <div class="controls">
                <input type="text" name="posamount70_80" class="input-normal" value="<?=$p[posamount70_80]?>" />
              </div>
            </div>
          </div>        <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>80万~90万(%)：</label>
              <div class="controls">
                <input type="text" name="paypercent80_90" class="input-normal" value="<?=$p[paypercent80_90]?>" />
              </div>
            </div>
            <div class="control-group  span8">
              <label class="control-label"><s>*</s>80万~90万：</label>
              <div class="controls">
                <input type="text" name="posamount80_90" class="input-normal" value="<?=$p[posamount80_90]?>" />
              </div>
            </div>
          </div>        <div class="row">
            <div class="control-group span8">
              <label class="control-label"><s>*</s>90万~100万%：</label>
              <div class="controls">
                <input type="text" name="paypercent90_100" class="input-normal" value="<?=$p[paypercent90_100]?>" />
              </div>
            </div>
            <div class="control-group  span9">
              <label class="control-label"><s>*</s>90万~100万：</label>
              <div class="controls">
                <input type="text" name="posamount90_100" class="input-normal" value="<?=$p[posamount90_100]?>" />
              </div>
            </div>
  
        </div>
        <hr>
        <div class="form-actions span5 offset3">
          <button id="btnSearch" type="submit" class="button button-primary">提交</button>
        </div>
      </form>
      <?php }?>
    </div>
  </div>
  <script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
  <script type="text/javascript" src="../assets/js/bui-min.js"></script> 
  <script type="text/javascript" src="../assets/js/config-min.js"></script> 
  <script type="text/javascript">
    BUI.use('common/page');
	
	function addfunds(){
		$.ajax({ 
				url : '<?=$base_url?>addfunds',
				type: 'POST',
				dataType : 'json',
				data :{amount:$('.addfunds').val()},
				success : function(data){
				  if(data.success){  
						BUI.Message.Alert('资金添加成功!',function(){
						location.reload() ;
							},'success');
				  }else{ 
					 
				  }	
				}
			});
}
  </script> 
  <script>
BUI.use(['bui/overlay', 'bui/grid', 'bui/data'],
function(Overlay, Grid, Data) {
	var Store = Data.Store,
	columns = [{
		title: '资金',
		dataIndex: 'amount',
		width: 100
	},
	{
		id: '123',
		title: '操作时间',
		dataIndex: 'created_at',
		width: 150
	},
	{
		title: '操作人',
		dataIndex: 'author',
		width: 100
	}],
	data = <?=$json_record?>;
	var store = new Store({
		data: data,
		autoLoad: true
	}),
	grid = new Grid.Grid({
		forceFit: true,
		// 列宽按百分比自适应
		columns: columns,
		store: store
	});

	var dialog = new Overlay.Dialog({
		title: '资金操作记录',
		width: 350,
		height: 370,
		children: [grid],
		childContainer: '.bui-stdmod-body',
		success: function() {
			//alert('确认');
			this.close();
		}
	});



	$('#btnShow').on('click',
	function() {
		dialog.show();
	});
	
	
	 var dialog2 = new Overlay.Dialog({
				title:'导出资金操作记录到EXCEL',
				width:500,
				height:250,
				bodyContent:"<p><a class='button button-success' href='../export_funds'>确定导出</a></p>",
				success:function () {
				
				this.close();
				}
			});	
						
	$('#btnExport').on('click',
	function() {
		dialog2.show();
	});
	
	
	
	
	 var dialog3 = new Overlay.Dialog({
				title:'清空资金操作记录',
				width:350,
				height:150,
				 buttons:[{
					text:'关闭',
					elCls : 'button',
					handler : function(){
					this.close();
					}
				}],
				bodyContent:"<p>清空后的操作记录不可恢复,您确定要清空吗?</p><p style='text-align:right;'><a class='button button-success' href='../funds_clear'>确定清空</a></p>",
				success:function () {
				
				this.close();
				}
			});	
						
	$('#btnClear').on('click',
	function() {
		dialog3.show();
	});	
		
});
  </script>
</div>
</body>
</html>